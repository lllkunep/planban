<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

defineProps({
    id: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(['close']);
const modalEl = ref(null);

const onHidden = () => emit('close');

onMounted(() => {
    modalEl.value.addEventListener('hidden.bs.modal', onHidden);
});

onBeforeUnmount(() => {
    modalEl.value.removeEventListener('hidden.bs.modal', onHidden);
});
</script>

<template>
    <div ref="modalEl" :id="id" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <slot name="header" />
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <slot name="body" />
                </div>
                <div class="modal-footer">
                    <slot name="footer" />
                </div>
            </div>
        </div>
    </div>
</template>