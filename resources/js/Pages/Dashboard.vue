<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import BoardListItem from "@/Components/BoardListItem.vue";

defineProps({
    boards: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2>
                Dashboard
            </h2>
        </template>
        <div class="container-sm py-5">
            <div class="row bg-secondary-subtle px-3 py-4 rounded shadow-sm">
                <div class="col-12">
                    <h3 class="px-4">Board list:</h3>
                    <div class="list-group">
                        <BoardListItem v-for="board in boards" :key="board.id" :board="board" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
