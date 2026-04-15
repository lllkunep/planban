<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    variant: {
        type: String,
        default: 'primary',
    },
    href: {
        type: String,
        default: '',
    },
    type: {
        type: String,
        default: 'submit',
    }
});
</script>

<template>
    <Link
        v-if="href"
        :href="href"
        :class="'btn btn-' + variant"
    >
        <slot />
    </Link>
    <button v-else :type="type" :class="'btn btn-' + variant">
        <slot />
    </button>
</template>
