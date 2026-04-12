<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
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
            You're logged in!
        </div>
    </AuthenticatedLayout>
</template>
