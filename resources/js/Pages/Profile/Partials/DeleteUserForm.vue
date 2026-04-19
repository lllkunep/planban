<script setup>
import Button from '@/Components/Common/Button.vue';
import Modal from '@/Components/Common/Modal.vue';
import FormField from '@/Components/Form/FormField.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useRoutes } from "@/composables/useRoutes.js";

const routes = useRoutes();

const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const deleteUser = () => {
    form.delete(routes.profile.destroy(), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section>
        <header>
            <h2>Delete Account</h2>
            <p class="mt-2 text-secondary">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Before deleting your account, please
                download any data or information that you wish to retain.
            </p>
        </header>

        <Button variant="danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal" type="button">
            Delete Account
        </Button>

        <Modal @close="closeModal" id="deleteAccountModal">
            <template #header>
                <h2>Are you sure you want to delete your account?</h2>
            </template>
            <template #body>
                <p>
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                </p>
                <FormField
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    placeholder="Password"
                    :message="form.errors.password"
                    @keyup.enter="deleteUser"
                />
            </template>
            <template #footer>
                <Button variant="secondary" data-bs-dismiss="modal">
                    Cancel
                </Button>
                <Button variant="danger" :disabled="form.processing" @click="deleteUser">
                    Delete Account
                </Button>
            </template>
        </Modal>
    </section>
</template>
