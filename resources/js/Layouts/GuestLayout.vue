<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import NavLink from "../Components/Nav/NavLink.vue";
import { useRoutes } from "@/composables/useRoutes.js";
const routes = useRoutes();

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
                    <NavLink
                        :href="routes.login()"
                        :active="route().current('login')"
                    >
                        Login
                    </NavLink>
                    <NavLink
                        :href="routes.register()"
                        :active="route().current('register')"
                    >
                        Register
                    </NavLink>
                </ul>
            </div>
        </div>
    </nav>
    <slot />
</template>
