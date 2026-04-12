<script setup>
import BoardLayout from '@/Layouts/BoardLayout.vue';
import Column from "@/Components/Column.vue";

import { Head } from '@inertiajs/vue3';

defineProps({
    board: {
        type: Object,
        required: true,
    }
})
</script>

<template>
    <Head :title="'PlanBan: ' + board.name" />
    <BoardLayout>
        <div class="d-flex gap-3 p-3 align-items-start overflow-auto"
             style="min-height: calc(100vh - 60px)">
            <Column
                v-for="column in board.columns" :key="column.id" :column="column"
            />
        </div>
    </BoardLayout>
</template>
