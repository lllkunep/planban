<script setup>

import { useId } from "vue";

const props = defineProps({
    label : {
        type: String,
        default: '',
    },
    buttonText: {
        type: String,
        default: 'Submit',
    },
    field: {
        type: String,
        required: true,
    },
    options: {
        type: Object,
        required: true,
    },
    track_by: {
        type: String,
        default: 'id',
    },
    label_by: {
        type: String,
        default: 'name',
    },
})

const form = defineModel('form', {
    type: Object,
    required: true,
});

defineEmits(['submit'])

const id = useId()
</script>

<template>
    <form @submit.prevent="$emit('submit')">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label :for="id" class="col-form-label">{{ label }}</label>
            </div>
            <div class="col-auto">
                <select
                    :class="['form-select', { 'is-invalid': form.errors[field] }]"
                    v-model="form[field]"
                >
                    <option
                        v-for="option in options"
                        :key="option[track_by]"
                        :value="option[track_by]"
                    >
                        {{ option[label_by] }}
                    </option>
                </select>
            </div>
            <div class="col-auto">
                <button
                    type="submit"
                    class="btn btn-primary text-nowrap"
                    :disabled="form.processing"
                >
                    {{ buttonText }}
                </button>
            </div>
        </div>
        <div class="text-danger" v-if="form.errors[field]?.length" v-for="error in form.errors[field]">{{ error }}</div>
        <div class="text-danger" v-else-if="form.errorMessage">{{ form.errorMessage }}</div>
        <div class="text-success" v-if="form.successMessage">{{ form.successMessage }}</div>
    </form>
</template>

<style scoped>

</style>
