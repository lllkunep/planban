<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from "@/Components/Sidebar.vue";
</script>

<template>
    <AuthenticatedLayout>
        <template #sidebar-toggler>
            <button class="btn btn-light float-start align-middle" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                <span class="navbar-toggler-icon"></span>
            </button>
        </template>
    </AuthenticatedLayout>
    <div class="d-flex">
        <Sidebar
            :boardId="$page.props.board.id"
        />
        <div class="flex-grow-1">
            <slot />
        </div>

    </div>


</template>
