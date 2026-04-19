<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import FormField from "@/Components/Form/FormField.vue";
import Button from "@/Components/Common/Button.vue";
import { useRoutes } from "@/composables/useRoutes.js";

const routes = useRoutes();

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(routes.password.email());
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="container-sm py-5">
            <div class="row justify-content-sm-center">
                <div class="col-12 col-sm-6 py-4 px-4 rounded bg-secondary-subtle">
                    <h2>Forgot Password</h2>
                    <p>
                        Forgot your password? No problem. Just let us know your email
                        address and we will email you a password reset link that will allow
                        you to choose a new one.
                    </p>
                    <form @submit.prevent="submit">
                        <FormField
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            label="Email"
                            :message="form.errors.email"
                        />
                        <Button variant="primary" :disabled="form.processing">
                            Email Password Reset Link
                        </Button>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
