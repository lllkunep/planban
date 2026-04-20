<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import axios from 'axios'
import draggable from 'vuedraggable'
import BoardLayout from '@/Layouts/BoardLayout.vue'
import Column from '@/Pages/Board/Show/Includes/Column.vue'
import CardShow from '@/Pages/Card/Show.vue'
import {useRoutes} from "@/composables/useRoutes.js";
import { useBoard } from "@/composables/useBoard.js";

const { currentBoard } = useBoard()

const routes = useRoutes()

function moveCard(column, card, position) {
    axios.patch(routes.boards.cards.move(column, card), { position: position })
}

const selectedCard = ref(null)
const sidebarLoading = ref(false)

async function openCard(card) {
    selectedCard.value = card
    sidebarLoading.value = true
    const { data } = await axios.get(routes.boards.cards.show(card))
    selectedCard.value = data.data
    sidebarLoading.value = false
}

function closeCard() {
    selectedCard.value = null
}

const localColumns = ref([...currentBoard.value.columns])
localColumns.value.push({id: null, name: '', cards: [], board_id: currentBoard.value.id, position: localColumns.value.length})

function columnAdded(){
    localColumns.value.push({id: null, name: '', cards: [], board_id: currentBoard.value.id, position: localColumns.value.length})
}

async function columnDeleted(columnId) {
    localColumns.value = localColumns.value.filter(column => column.id !== columnId)
}

function handleColumnChange(event) {
    const item = event.moved
    if (!item) return

    axios.patch(routes.boards.columns.move(item.element), { position: item.newIndex })
}
</script>

<template>
    <Head :title="'PlanBan: ' + currentBoard.name" />
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
        >
            <template #item="{ element }">
                <Column
                    :column="element"
                    @card-moved="moveCard"
                    @card-selected="openCard"
                    @column-deleted="columnDeleted"
                    @column-added="columnAdded"
                />
            </template>
        </draggable>
        <CardShow
            :card="selectedCard"
            :loading="sidebarLoading"
            @close="closeCard"
        />
    </BoardLayout>
</template>
