<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import axios from 'axios'
import draggable from 'vuedraggable'
import BoardLayout from '@/Layouts/BoardLayout.vue'
import Column from '@/Components/Board/Column.vue'
import CardSidebar from '@/Components/Card/CardSidebar.vue'

const props = defineProps({
    board: {
        type: Object,
        required: true,
    },
})

const localColumns = ref([...props.board.columns])

function moveCard({ cardId, toColumnId, position }) {
    axios.patch(route('cards.move', cardId), { column_id: toColumnId, position })
}

function handleColumnChange(event) {
    const item = event.moved
    if (!item) return

    axios.patch(route('columns.move', item.element.id), { position: item.newIndex })
}

const selectedCard = ref(null)
const sidebarLoading = ref(false)

async function openCard(card) {
    selectedCard.value = card
    sidebarLoading.value = true
    const { data } = await axios.get(route('cards.show', card.id))
    selectedCard.value = data
    sidebarLoading.value = false
}

function closeCard() {
    selectedCard.value = null
}
</script>

<template>
    <Head :title="'PlanBan: ' + board.name" />
    <BoardLayout>
        <draggable
            v-model="localColumns"
            item-key="id"
            handle=".column-handle"
            class="d-flex gap-3 p-3 align-items-start overflow-auto"
            style="height: calc(100vh - 60px)"
            ghost-class="drag-ghost"
            @change="handleColumnChange"
        >
            <template #item="{ element }">
                <Column
                    :column="element"
                    @card-moved="moveCard"
                    @card-selected="openCard"
                />
            </template>
        </draggable>

        <CardSidebar
            :card="selectedCard"
            :loading="sidebarLoading"
            @close="closeCard"
        />
    </BoardLayout>
</template>
