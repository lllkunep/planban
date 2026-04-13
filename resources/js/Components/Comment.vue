<script setup>
defineProps({
    comment: {
        type: Object,
        required: true,
    }
})
</script>

<template>
    <div
        :key="comment.id"
        class="p-2 mb-1 mb-3"
    >
        <h5>{{ comment.user?.name }}</h5>
        <p class="mb-0 text-muted">{{ comment.text }}</p>
    </div>
</template>

<style scoped>

</style>
