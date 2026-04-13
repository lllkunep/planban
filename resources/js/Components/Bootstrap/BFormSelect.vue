<script setup>
defineProps({
    modelValue: {
        type: Number,
        default: 0,
    },
    options: {
        type: Object,
        required: true,
    },
    label: {
        type: String,
        default: '',
    },
    message: {
        type: String,
    },
    track_by: {
        type: String,
        default: 'id',
    },
    label_by: {
        type: String,
        default: 'name',
    },
});

const model = defineModel({
    type: [Number, null],
    required: true,
});

defineOptions({ inheritAttrs: false })
</script>

<template>
    <div class="mb-3">
        <div class="row ps-3 g-3 align-items-center">
            <div v-if="label" class="col-auto">
                <label :for="$attrs.id" class="col-form-label">{{ label }}</label>
            </div>
            <div class="col-auto">
                <select
                    :class="['form-select', { 'is-invalid': message }]"
                    v-bind="$attrs"
                    v-model="model"
                >
                    <option
                        v-for="option in options"
                        :key="option[track_by]"
                        :value="option[track_by]"
                    >
                        {{ option[label_by] }}
                    </option>
                </select>
                <div class="invalid-feedback" v-if="message">{{ message }}</div>
            </div>
        </div>
    </div>
</template>
