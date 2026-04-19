<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Button from "@/Components/Common/Button.vue";
import FormField from "@/Components/Form/FormField.vue";
import { useRoutes } from "@/composables/useRoutes.js";

const routes = useRoutes();

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(routes.register(), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="container-sm py-5">
            <div class="row justify-content-sm-center">
                <div class="col-12 col-sm-6 py-4 px-4 rounded bg-secondary-subtle">
                    <h2 class="mb-4">Register</h2>
                    <form @submit.prevent="submit">
                        <FormField
                            id="name"
                            type="text"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                            label="Name"
                            :message="form.errors.name"
                        />
                        <FormField
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            label="Email"
                            :message="form.errors.email"
                        />
                        <FormField
                            id="password"
                            type="password"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                            label="Password"
                            :message="form.errors.password"
                        />
                        <FormField
                            id="password_confirmation"
                            type="password"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                            label="Confirm Password"
                            :message="form.errors.password_confirmation"
                        />
                        <Button variant="primary" :disabled="form.processing">
                            Register
                        </Button>
                        <Link
                            :href="routes.login()"
                            class="float-end link-secondary"
                        >
                            Already registered?
                        </Link>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
