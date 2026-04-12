<script setup>
import BNavbar from "@/Components/Bootstrap/BNavbar.vue";
import BNavLink from "@/Components/Bootstrap/BNavLink.vue";
import BDropdown from "@/Components/Bootstrap/BDropdown.vue";
import BDropdownLink from "@/Components/Bootstrap/BDropdownLink.vue";
</script>

<template>
    <BNavbar>
        <template #sidebar-toggler>
            <slot name="sidebar-toggler" />
        </template>
        <template #nav-items>
            <BNavLink
                :href="route('dashboard')"
                :active="route().current('dashboard')"
            >
                Dashboard
            </BNavLink>
        </template>
        <template #nav-right>
            <BDropdown
                :title="$page.props.auth.user.name"
            >
                <BDropdownLink
                    :href="route('profile.edit')"
                >
                    Profile
                </BDropdownLink>
                <BDropdownLink
                    :href="route('logout')"
                    method="post"
                    as="button"
                >
                    Log Out
                </BDropdownLink>
            </BDropdown>
        </template>
    </BNavbar>

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
