<script setup>
const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
})

const emit = defineEmits(['card-selected'])
</script>

<template>
    <div class="d-flex flex-column gap-2 bg-light rounded-bottom p-2">
        <div class="card shadow-sm" style="cursor: grab" @click="emit('card-selected', card)">
            <div class="card-body p-2">
                <p class="mb-0 small">{{ card.name }}</p>
            </div>
        </div>
    </div>
</template>
