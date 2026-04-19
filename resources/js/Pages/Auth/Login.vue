<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import FormField from "@/Components/Form/FormField.vue";
import FormCheckbox from "@/Components/Form/FormCheckbox.vue";
import Button from "@/Components/Common/Button.vue";
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useRoutes } from "@/composables/useRoutes.js";

const routes = useRoutes();

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(routes.login(), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="container-sm py-5">
            <div class="row justify-content-sm-center">
                <div class="col-12 col-sm-6 py-4 px-4 rounded bg-secondary-subtle">
                    <h2 class="mb-4">Log in</h2>
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
                        <FormField
                            id="password"
                            type="password"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            label="Password"
                            :message="form.errors.password"
                        />
                        <FormCheckbox
                            id="remember"
                            name="remember"
                            label="Remember me"
                            v-model:checked="form.remember"
                        />
                        <Button variant="primary" :disabled="form.processing">Log in</Button>
                        <Link
                            v-if="canResetPassword"
                            :href="routes.password.request()"
                            class="float-end link-secondary"
                        >
                            Forgot your password?
                        </Link>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
