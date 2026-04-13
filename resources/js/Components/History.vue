<script setup>
defineProps({
    history: {
        type: Object,
        required: true,
    }
})
</script>

<template>
    <div>
        <p class="mb-0 text-muted"><b>{{ history.user?.name }}</b> : {{ history.text }}</p>
    </div>
</template>

<style scoped>

</style>
