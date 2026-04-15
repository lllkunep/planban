<script setup>
import Navbar from "@/Components/Nav/Navbar.vue";
import NavLink from "@/Components/Nav/NavLink.vue";
import Dropdown from "@/Components/Nav/Dropdown.vue";
import DropdownLink from "@/Components/Nav/DropdownLink.vue";
</script>

<template>
    <Navbar>
        <template #sidebar-toggler>
            <slot name="sidebar-toggler" />
        </template>
        <template #nav-items>
            <NavLink
                :href="route('dashboard')"
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
                    :href="route('profile.edit')"
                >
                    Profile
                </DropdownLink>
                <DropdownLink
                    :href="route('logout')"
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
