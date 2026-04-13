<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import axios from 'axios'
import draggable from 'vuedraggable'
import BoardLayout from '@/Layouts/BoardLayout.vue'
import Column from '@/Components/Column.vue'

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
                />
            </template>
        </draggable>
    </BoardLayout>
</template>
