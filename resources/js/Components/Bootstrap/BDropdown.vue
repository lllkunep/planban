<script setup>
const props = defineProps({
    title: {
        type: String,
        required: true,
    },
});

defineOptions({ inheritAttrs: false })
</script>

<template>
    <li class="nav-item dropdown">
        <a v-bind="$attrs" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{ title }}
        </a>
        <ul class="dropdown-menu">
            <slot />
        </ul>
    </li>
</template>
