<script setup>
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.css'

const props = defineProps({
    options: {
        type: Array,
        required: true,
    }
})

const model = defineModel({
    type: Array,
    required: false,
    default: () => []
});
</script>

<template>
    <Multiselect
        class="mb-3"
        v-model="model"
        :options="props.options"
        :multiple="true"
    >
        <template #tag="{ option, remove }">
            <span
                class="badge me-1"
                :style="{ backgroundColor: option.color }"
            >
                {{ option.name }}
                <span @click="remove(option)" style="cursor: pointer;">✕</span>
            </span>
        </template>
        <template #option="{ option }">
            <div class="d-flex align-items-center gap-2">
            <span
                class="rounded-circle"
                style="width: 12px; height: 12px; flex-shrink: 0;"
                :style="{ backgroundColor: option.color }"
            ></span>
                <span>{{ option.name }}</span>
            </div>
        </template>
    </Multiselect>
</template>

<style scoped>
:deep(.multiselect__option--highlight) {
    background: #999999;
    color: #000000;
}
</style>
