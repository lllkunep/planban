<script setup>
import BButton from '@/Components/Bootstrap/BButton.vue';
import BModal from '@/Components/Bootstrap/BModal.vue';
import BFormField from '@/Components/Bootstrap/BFormField.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
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

        <BButton variant="danger" text="Delete Account"
                data-bs-toggle="modal"
                data-bs-target="#deleteAccountModal"
                type="button"
        />

        <BModal @close="closeModal" id="deleteAccountModal">
            <template #header>
                <h2>Are you sure you want to delete your account?</h2>
            </template>
            <template #body>
                <p>
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                </p>
                <BFormField
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
                <BButton variant="secondary" text="Cancel" data-bs-dismiss="modal" />
                <BButton variant="danger" text="Delete Account" :disabled="form.processing" @click="deleteUser" />
            </template>
        </BModal>
    </section>
</template>
