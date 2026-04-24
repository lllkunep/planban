<script setup>

import History from "@/Pages/Card/Includes/History.vue";

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
});

</script>

<template>
    <div v-if="card.histories?.length" class="my-3">
        <hr>
        <label class="form-label fw-semibold text-muted">History</label>
        <History v-for="history in card.histories" :key="history.id" :history="history" />
    </div>
</template>

<style scoped>

</style>
