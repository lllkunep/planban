<script setup>
import Button from "@/Components/Common/Button.vue";

defineProps({
    comment: {
        type: Object,
        required: true,
    }
})

defineEmits(['edit'])

</script>

<template>
    <div
        :key="comment.id"
        class="p-2 mb-1 mb-3"
    >
        <h5>{{ comment.user?.name }}</h5>
        <p class="mb-0 text-muted">{{ comment.text }}</p>
        <Button variant="link" class="p-0 text-decoration-none" size="sm" @click="$emit('edit')">Edit</Button>
    </div>
</template>

<style scoped>

</style>
