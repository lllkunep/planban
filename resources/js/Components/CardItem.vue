<script setup>
defineProps({
    card: {
        type: Object,
        required: true,
    }
})
</script>

<template>
    <div class="d-flex flex-column gap-2 bg-light rounded-bottom p-2">
        <div class="card shadow-sm">
            <div class="card-body p-2">
                <p class="mb-0 small">{{ card.name }}</p>
            </div>
        </div>
    </div>
</template>
