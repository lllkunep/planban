<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import BFormField from "@/Components/Bootstrap/BFormField.vue";
import BFormCheckbox from "@/Components/Bootstrap/BFormCheckbox.vue";
import BButton from "@/Components/Bootstrap/BButton.vue";
import { Head, Link, useForm } from '@inertiajs/vue3';

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
    form.post(route('login'), {
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
                        <BFormField
                            id="password"
                            type="password"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            label="Password"
                            :message="form.errors.password"
                        />
                        <BFormCheckbox
                            id="remember"
                            name="remember"
                            label="Remember me"
                            v-model:checked="form.remember"
                        />
                        <BButton
                            variant="primary"
                            text="Log in"
                            :disabled="form.processing"
                        />
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
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
