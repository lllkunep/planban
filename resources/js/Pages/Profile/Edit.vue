<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2>
                Profile
            </h2>
        </template>
        <div class="container-sm">
            <div class="row mt-5">
                <div class="col-12 py-5 px-4 border rounded shadow-sm">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                    />
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 py-5 px-4 border rounded shadow-sm">
                    <UpdatePasswordForm />
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 py-5 px-4 border rounded shadow-sm">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
