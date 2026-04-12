<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    variant: {
        type: String,
        default: 'primary',
    },
    text: {
        type: String,
        default: '',
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
        {{ text }}
    </Link>
    <button v-else :type="type" :class="'btn btn-' + variant">{{ text }}</button>
</template>
