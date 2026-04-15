<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Button from "@/Components/Common/Button.vue";
import FormField from "@/Components/Form/FormField.vue";

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />
        <div class="container-sm py-5">
            <div class="row justify-content-sm-center">
                <div class="col-12 col-sm-6 py-4 px-4 rounded bg-secondary-subtle">
                    <h2>Confirm Password</h2>
                    <p>
                        This is a secure area of the application. Please confirm your
                        password before continuing.
                    </p>
                    <form @submit.prevent="submit">
                        <FormField
                            id="password"
                            type="password"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            autofocus
                            label="Password"
                            :message="form.errors.password"
                        />
                        <Button
                            variant="primary"
                            text="Confirm"
                            :disabled="form.processing"
                        />
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
