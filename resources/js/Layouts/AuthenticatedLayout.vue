<script setup>
import Navbar from "@/Components/Nav/Navbar.vue";
import NavLink from "@/Components/Nav/NavLink.vue";
import Dropdown from "@/Components/Nav/Dropdown.vue";
import DropdownLink from "@/Components/Nav/DropdownLink.vue";
import { useRoutes } from "@/composables/useRoutes.js";

const routes = useRoutes();

</script>

<template>
    <Navbar>
        <template #sidebar-toggler>
            <slot name="sidebar-toggler" />
        </template>
        <template #nav-items>
            <NavLink
                :href="routes.dashboard()"
                :active="route().current('dashboard')"
            >
                Dashboard
            </NavLink>
        </template>
        <template #nav-right>
            <Dropdown
                :title="$page.props.auth.user.name"
            >
                <DropdownLink
                    :href="routes.profile.edit()"
                >
                    Profile
                </DropdownLink>
                <DropdownLink
                    :href="routes.auth.logout()"
                    method="post"
                    as="button"
                >
                    Log Out
                </DropdownLink>
            </Dropdown>
        </template>
    </Navbar>

    <!-- Page Heading -->
    <header
        class="shadow-sm bg-body py-2"
        v-if="$slots.header"
    >
        <div class="container-sm">
            <slot name="header" />
        </div>
    </header>

    <!-- Page Content -->
    <main>
        <div class="container-sm">
            <slot />
        </div>
    </main>
</template>
