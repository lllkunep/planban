<script setup>
import {computed, useId} from 'vue';

const model = defineModel()

const props = defineProps({
    placeholder: {
        type: String,
        default: '',
    },
    height: {
        type: String,
        default: '300px',
    },
})

const styles = computed(() =>
    props.height ? 'height:' + props.height + '; width: 100%;' : 'width: 100%;',
);

defineOptions({ inheritAttrs: false })

const id = useId();
</script>

<template>
    <div class="form-floating">
        <textarea
            v-model="model"
            class="form-control"
            :placeholder="placeholder"
            :id="id" style="height: 100px"
            :style="styles"
            v-bind="$attrs" />
        <label :for="id">{{ placeholder }}</label>
    </div>
</template>

<style scoped>

</style>
