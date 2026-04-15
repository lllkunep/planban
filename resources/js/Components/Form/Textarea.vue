<script setup>
import { computed } from 'vue';

const props = defineProps({
    id: {
        type: String,
        required: true,
    },
    modelValue: {
        type: [String, null],
        default: null,
    },
    placeholder: {
        type: String,
        default: '',
    },
    height: {
        type: String,
        default: '300px',
    },
    size: {
        type: String,
        default: '',
    },
})

const classes = computed(() =>
    props.size ? 'form-control-' + props.size : 'form-control',
);

const styles = computed(() =>
    props.height ? 'height:' + props.height + '; width: 100%;' : 'width: 100%;',
);

defineEmits(['update:modelValue'])
</script>

<template>
    <textarea
        :id="id"
        :class="classes"
        :placeholder="placeholder"
        :value="modelValue"
        :style="styles"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>

<style scoped>

</style>
