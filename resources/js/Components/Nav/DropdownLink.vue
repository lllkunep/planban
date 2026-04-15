<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    href: {
        type: String,
        required: true,
    },
});

defineOptions({ inheritAttrs: false })
</script>

<template>
    <li>
        <Link
            :href="href"
            class="dropdown-item"
            v-bind="$attrs"
        >
            <slot />
        </Link>
    </li>
</template>
