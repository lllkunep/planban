<script setup>

import {useId} from "vue";

const props = defineProps({
    label: {
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
    message: {
        type: String,
        default: '',
    },
    disabled: {
        type: Boolean,
        default: false,
    }
})

const form = defineModel('form', {
    type: Object,
    required: true,
});

defineEmits(['submit'])

const id = useId();
</script>

<template>
    <form @submit.prevent="$emit('submit')">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label :for="id" class="col-form-label">{{ label }}</label>
            </div>
            <div class="col-auto">
                <input
                    v-model="form[field]"
                    type="text"
                    :id="id"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors[field] }"
                    :disabled="disabled"
                >
            </div>
            <div class="col-auto">
                <button
                    v-if="!disabled"
                    type="submit"
                    class="btn btn-primary text-nowrap"
                    :disabled="form.processing"
                >
                    {{ buttonText }}
                </button>
            </div>
        </div>
        <div class="text-danger" v-if="form.errors" v-for="error in form.errors[field]">{{ error }}</div>
        <div class="text-danger" v-else-if="form.errorMessage">{{ form.errorMessage }}</div>
        <div class="text-success" v-if="form.successMessage">{{ form.successMessage }}</div>
    </form>
</template>

<style scoped>

</style>
