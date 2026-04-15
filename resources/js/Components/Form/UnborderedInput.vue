<script setup>
defineProps({
    value: {
        type: String,
        default: '',
    }
})
</script>

<template>
    <input
        :value="value"
        class="form-control form-control-sm border-0 bg-transparent fw-bold me-1"
        style="cursor: text"
    />
</template>

<style scoped>

</style>
