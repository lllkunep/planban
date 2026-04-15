<script setup>
import {onMounted, ref, useId} from 'vue';

defineProps({
    label: {
        type: String,
        default: '',
    },
    help: {
        type: String,
        default: '',
    },
    message: {
        type: String,
    }
});

const model = defineModel({
    type: String,
    required: true,
});

defineOptions({ inheritAttrs: false })

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });

const helpId = useId();
</script>

<template>
    <div class="mb-3">
        <label v-if="label" :for="$attrs.id" class="form-label">{{ label }}</label>
        <input ref="input" v-model="model" v-bind="$attrs" :class="['form-control', { 'is-invalid': message }]" :aria-describedby="helpId">
        <div v-if="help" :id="helpId" class="form-text">{{ help }}</div>
        <div class="invalid-feedback" v-if="message">
            {{ message }}
        </div>
    </div>
</template>
