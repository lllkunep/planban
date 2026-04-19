<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Button from "@/Components/Common/Button.vue";
import { useRoutes } from "@/composables/useRoutes.js";

const routes = useRoutes();

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(routes.verification.send());
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="container-sm py-5">
            <div class="row justify-content-sm-center">
                <div class="col-12 col-sm-6 py-4 px-4 rounded bg-secondary-subtle">
                    <h2>Email Verification</h2>
                    <p>
                        Thanks for signing up! Before getting started, could you verify your
                        email address by clicking on the link we just emailed to you? If you
                        didn't receive the email, we will gladly send you another.
                    </p>
                    <p
                        class="text-success"
                        v-if="verificationLinkSent"
                    >
                        A new verification link has been sent to the email address you
                        provided during registration.
                    </p>
                    <form @submit.prevent="submit">
                        <Button variant="primary" :disabled="form.processing">
                            Resend Verification Email
                        </Button>
                        <Link
                            :href="routes.logout()"
                            method="post"
                            as="button"
                            class="float-end link-secondary"
                        >
                            Log Out
                        </Link>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
