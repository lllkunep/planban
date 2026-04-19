<script setup>
import FormField from '@/Components/Form/FormField.vue';
import Button from '@/Components/Common/Button.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useRoutes } from "@/composables/useRoutes.js";

const routes = useRoutes();

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(routes.password.update(), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2>
                Update Password
            </h2>

            <p class="mt-2 text-secondary">
                Ensure your account is using a long, random password to stay
                secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword">
            <FormField
                id="current_password"
                ref="currentPasswordInput"
                v-model="form.current_password"
                type="password"
                autocomplete="current-password"
                label="Current Password"
                :message="form.errors.current_password"
            />
            <FormField
                id="password"
                ref="passwordInput"
                v-model="form.password"
                type="password"
                autocomplete="new-password"
                label="New Password"
                :message="form.errors.password"
            />
            <FormField
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                autocomplete="new-password"
                label="Confirm Password"
                :message="form.errors.password_confirmation"
            />
            <Button variant="primary" :disabled="form.processing">
                Save
            </Button>
        </form>
    </section>
</template>
