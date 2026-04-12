<script setup>

import CardItem from "@/Components/CardItem.vue";

defineProps({
    column: {
        type: Object,
        required: true,
    }
})
</script>

<template>
    <div class="flex-shrink-0" style="width: 280px">

        <div class="d-flex justify-content-between align-items-center
                    bg-light rounded-top p-2">
            <input
                class="form-control form-control-sm border-0 bg-transparent fw-bold"
                :value="column.name"
            />
            <button class="btn btn-sm btn-link text-muted">✕</button>
        </div>

        <button class="btn btn-sm btn-light w-100 mt-1 text-muted">
            + Add card
        </button>
        <slot />
        <CardItem v-for="card in column.cards" :key="card.id" :card="card" />
    </div>
</template>
