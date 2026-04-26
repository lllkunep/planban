<script setup>
const props = defineProps({
    color: {
        type: String,
        required: true,
    },
    asInput: {
        type: Boolean,
        default: false
    }
});

const text = defineModel('text', {
    type: String,
    required: true,
});

const emit = defineEmits(['change']);

const originalText = text.value;

function getTextColor(hexColor) {
    const hex = hexColor.replace('#', '')
    const r = parseInt(hex.substring(0, 2), 16)
    const g = parseInt(hex.substring(2, 4), 16)
    const b = parseInt(hex.substring(4, 6), 16)
    const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255
    return luminance > 0.5 ? '#000000' : '#ffffff'
}

const defaultClasses = 'border-0 fw-bold font-monospace me-1 text-center pt-1';

function onChange($event) {
    if (text.value === ''){
        text.value = originalText;
        return;
    }
    emit('change', $event);
}

</script>

<template>
    <input
        v-if="asInput"
        :class="defaultClasses"
        v-model="text"
        :style="{ width: (text != '' ? text.length + 3 : $attrs.placeholder.length + 3) + 'ch', color: getTextColor(color), backgroundColor: color}"
        style="cursor: text; border-radius: 10px;"
        @change="onChange( $event )"
    />
    <span v-else
          :class="defaultClasses"
          :style="{ width: (text.length + 3) + 'ch', color: getTextColor(color), backgroundColor: color}"
          style="cursor: text; border-radius: 10px;display: inline-block;"
    >
        {{ text }}
    </span>
</template>

<style scoped>

</style>
