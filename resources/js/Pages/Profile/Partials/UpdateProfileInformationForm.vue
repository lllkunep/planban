<script setup>
import FormField from '@/Components/Form/FormField.vue';
import Button from '@/Components/Common/Button.vue';
import {useForm, usePage} from "@inertiajs/vue3";

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2>
                Profile Information
            </h2>

            <p class="mt-2 text-secondary">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
        >
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
            <Button variant="primary" :disabled="form.processing">
                Save
            </Button>
        </form>
    </section>
</template>
