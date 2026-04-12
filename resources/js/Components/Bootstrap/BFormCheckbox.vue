<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        required: true,
    },
    value: {
        default: null,
    },
    label: {
        type: String,
        default: '',
    },
    message: {
        type: String,
    }
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});

defineOptions({ inheritAttrs: false })
</script>

<template>
    <div class="mb-3 form-check">
        <input v-model="proxyChecked" v-bind="$attrs" :class="['form-check-input', { 'is-invalid': message }]" type="checkbox">
        <label class="form-check-label" :for="$attrs.id">
            {{ label }}
        </label>
        <div class="invalid-feedback" v-if="message">
            {{ message }}
        </div>
    </div>
</template>
