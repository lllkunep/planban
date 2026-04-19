<script setup>
import {Link} from "@inertiajs/vue3";
import { useRoutes } from "@/composables/useRoutes.js";

const routes = useRoutes();

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
    <Link :href="routes.boards.show(board)"
          class="list-group-item list-group-item-action"
          :class="{ 'active': active }"
    >
        {{ board.name }}
    </Link>
</template>
