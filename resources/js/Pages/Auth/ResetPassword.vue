<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Button from "@/Components/Common/Button.vue";
import FormField from "@/Components/Form/FormField.vue";

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="container-sm py-5">
            <div class="row justify-content-sm-center">
                <div class="col-12 col-sm-6 py-4 px-4 rounded bg-secondary-subtle">
                    <h2>Reset Password</h2>
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
                            Reset Password
                        </Button>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
