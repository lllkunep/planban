<script setup>

</script>

<template>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <slot />
        <div class="actions d-flex gap-1">
            <slot name="actions" />
        </div>
    </li>
</template>

<style scoped>

</style>
