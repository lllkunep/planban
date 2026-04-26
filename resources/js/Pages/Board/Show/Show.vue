<script setup>
import {onMounted, ref} from 'vue'
import {Head} from '@inertiajs/vue3'
import axios from 'axios'
import draggable from 'vuedraggable'
import BoardLayout from '@/Layouts/BoardLayout.vue'
import Column from '@/Pages/Board/Show/Includes/Column.vue'
import CardShow from '@/Pages/Card/Show.vue'
import {useRoutes} from "@/composables/useRoutes.js";
import {useBoard} from "@/composables/useBoard.js";
import {useToast} from '@/composables/useToast'

const routes = useRoutes()
const {currentBoard} = useBoard()
const toast = useToast()

const props = defineProps({
    initialCard: {type: Object, default: null},
})

const selectedCard = ref(null)
const sidebarLoading = ref(false)
const localColumns = ref([...currentBoard.value.columns])

let columnsSnapshot = []

onMounted(() => {
    localColumns.value.push({
        id: null,
        name: '',
        cards: [],
        board_id: currentBoard.value.id,
        position: localColumns.value.length
    })
    if (props.initialCard) openCard(props.initialCard)
})

function onStart() {
    columnsSnapshot = JSON.parse(JSON.stringify(localColumns.value))
}

function columnAdded() {
    localColumns.value.push({
        id: null,
        name: '',
        cards: [],
        board_id: currentBoard.value.id,
        position: localColumns.value.length
    })
}

async function columnDeleted(columnId) {
    localColumns.value = localColumns.value.filter(column => column.id !== columnId)
}

async function handleColumnChange(event) {
    const item = event.moved
    if (!item) return
    try {
        const newIndex = localColumns.value.length - 2 - item.newIndex
        await axios.patch(routes.boards.columns.move(item.element), {position: newIndex, ololo: item.newIndex})
    } catch (error) {
        const message = error.response?.data.message ?? 'Something went wrong';
        toast.error(message)
        localColumns.value = columnsSnapshot
    }
}

function findCardInBoard(cardId) {
    for (const col of currentBoard.value.columns) {
        const found = col.cards.find(c => c.id == cardId)
        if (found) return found
    }
    return null
}

async function openCard(card) {
    history.replaceState(null, '', routes.boards.onCard(card))
    try {
        sidebarLoading.value = true
        const boardCard = findCardInBoard(card.id)
        selectedCard.value = boardCard ?? card
        const {data} = await axios.get(routes.boards.cards.show(card))
        Object.assign(selectedCard.value, data.data)
        sidebarLoading.value = false
    } catch (error) {
        const message = error.response?.data.message ?? 'Something went wrong';
        toast.error(message)
        selectedCard.value = null
        sidebarLoading.value = false
        history.replaceState(null, '', routes.boards.show(currentBoard.value))
    }
}

function closeCard() {
    selectedCard.value = null
    history.replaceState(null, '', routes.boards.show(currentBoard.value))
}

function cardDeleted(cardId) {
    closeCard()
    const card = findCardInBoard(cardId)
    if (card) {
        const column = localColumns.value.find(c => c.cards.includes(card))
        if (column) {
            column.cards = column.cards.filter(c => c.id !== cardId)
        }
    }
}

async function moveCard(column, card, position) {
    try {
        await axios.patch(routes.boards.cards.move(column, card), {position: position})
    } catch (error) {
        const message = error.response?.data.message ?? 'Something went wrong';
        toast.error(message)
        localColumns.value = columnsSnapshot
    }
}
</script>

<template>
    <Head :title="'PlanBan: ' + currentBoard.name"/>
    <BoardLayout>
        <draggable
            v-model="localColumns"
            item-key="id"
            handle=".column-handle"
            class="d-flex gap-3 p-3 align-items-start overflow-auto"
            style="height: calc(100vh - 60px)"
            ghost-class="drag-ghost"
            @change="handleColumnChange"
            :move="(event) => Boolean(event.draggedContext.element.id)"
            @start="onStart"
        >
            <template #item="{ element }">
                <Column
                    :column="element"
                    @card-moved="moveCard"
                    @card-selected="openCard"
                    @column-deleted="columnDeleted"
                    @column-added="columnAdded"
                    @card-move-start="onStart"
                />
            </template>
        </draggable>
        <CardShow
            :card="selectedCard"
            :loading="sidebarLoading"
            @close="closeCard"
            @deleted="cardDeleted"
        />
    </BoardLayout>
</template>
