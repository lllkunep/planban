<script setup>
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.css'
import Tag from "@/Components/Common/Tag.vue";

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
            <Tag :text="option.name" :color="option.color" /><span @click="remove(option)" style="cursor: pointer;">✕</span>
        </template>
        <template #option="{ option }">
            <Tag :text="option.name" :color="option.color" />
        </template>
    </Multiselect>
</template>

<style scoped>
:deep(.multiselect__option--highlight) {
    background: #999999;
    color: #000000;
}
</style>
