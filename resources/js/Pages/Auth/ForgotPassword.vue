<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import BFormField from "@/Components/Bootstrap/BFormField.vue";
import BButton from "@/Components/Bootstrap/BButton.vue";

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
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
                        <BFormField
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            label="Email"
                            :message="form.errors.email"
                        />
                        <BButton
                            variant="primary"
                            text="Email Password Reset Link"
                            :disabled="form.processing"
                        />
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
