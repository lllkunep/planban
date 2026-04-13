<script setup>
import { Head } from '@inertiajs/vue3'
import axios from 'axios'
import BoardLayout from '@/Layouts/BoardLayout.vue'
import Column from '@/Components/Column.vue'

const props = defineProps({
    board: {
        type: Object,
        required: true,
    },
})

function moveCard({ cardId, toColumnId, afterCardId }) {
    axios.patch(route('cards.move', cardId), { column_id: toColumnId, after_card_id: afterCardId })
}
</script>

<template>
    <Head :title="'PlanBan: ' + board.name" />
    <BoardLayout>
        <div class="d-flex gap-3 p-3 align-items-start overflow-auto"
             style="height: calc(100vh - 60px)">
            <Column
                v-for="column in board.columns"
                :key="column.id"
                :column="column"
                @card-moved="moveCard"
            />
        </div>
    </BoardLayout>
</template>
