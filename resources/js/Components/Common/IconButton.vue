<script setup>
import Button from "@/Components/Common/Button.vue";
defineProps({
    icon: {
        type: String,
        required: true,
    },
    variant: {
        type: String,
        default: 'light',
    }
})
</script>

<template>
    <Button :variant="variant">
        <i :class="'bi bi-' + icon"></i>
    </Button>
</template>

<style scoped>

</style>
