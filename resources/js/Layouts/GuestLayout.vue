<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import BNavLink from "../Components/Bootstrap/BNavLink.vue";
</script>

<template>
    <nav class="navbar navbar-expand-sm bg-body-tertiary">
        <div class="container-sm">
            <Link href="/">
                <ApplicationLogo
                    width="30" height="24"
                />
            </Link>
            <div class="d-flex">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <BNavLink
                        :href="route('login')"
                        :active="route().current('login')"
                    >
                        Login
                    </BNavLink>
                    <BNavLink
                        :href="route('register')"
                        :active="route().current('register')"
                    >
                        Register
                    </BNavLink>
                </ul>
            </div>
        </div>
    </nav>
    <slot />
</template>
