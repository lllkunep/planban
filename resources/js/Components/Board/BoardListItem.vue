<script setup>
import {Link} from "@inertiajs/vue3";
const props = defineProps({
    board: {
        type: Object,
        required: true,
    },
    active: {
        type: Boolean,
        default: false,
    }
})
</script>

<template>
    <Link :href="route('boards.show', board.id)"
          class="list-group-item list-group-item-action"
          :class="{ 'active': active }"
    >
        {{ board.name }}
    </Link>
</template>
