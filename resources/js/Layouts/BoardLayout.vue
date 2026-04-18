<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from "@/Components/Board/Sidebar.vue";
</script>

<template>
    <div class="d-flex flex-column vh-100 overflow-hidden">
        <AuthenticatedLayout>
            <template #sidebar-toggler>
                <button class="btn btn-light float-start align-middle" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </template>
        </AuthenticatedLayout>
        <div class="d-flex flex-grow-1 overflow-hidden">
            <Sidebar
                :boardId="$page.props.board.id"
            />
            <div class="flex-grow-1 overflow-auto">
                <slot />
            </div>
        </div>
    </div>
</template>
