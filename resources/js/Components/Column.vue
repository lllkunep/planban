<script setup>
import { ref } from 'vue'
import draggable from 'vuedraggable'
import CardItem from '@/Components/CardItem.vue'

const props = defineProps({
    column: {
        type: Object,
        required: true,
    },
})

const emit = defineEmits(['card-moved'])

const localCards = ref([...props.column.cards])

function handleChange(event) {
    const item = event.added ?? event.moved
    if (!item) return

    const newIndex = item.newIndex
    emit('card-moved', {
        cardId: item.element.id,
        toColumnId: props.column.id,
        position: newIndex,
    })
}
</script>

<template>
    <div class="flex-shrink-0" style="width: 280px">

        <div class="d-flex justify-content-between align-items-center
                    bg-light rounded-top p-2">
            <input
                class="form-control form-control-sm border-0 bg-transparent fw-bold"
                :value="column.name"
            />
            <button class="btn btn-sm btn-link text-muted">✕</button>
        </div>

        <draggable
            v-model="localCards"
            group="cards"
            item-key="id"
            class="rounded-bottom p-2"
            style="min-height: 60px; background-color: #f8f9fa"
            ghost-class="drag-ghost"
            drag-class="drag-active"
            @change="handleChange"
        >
            <template #item="{ element }">
                <CardItem :card="element" />
            </template>
        </draggable>

        <button class="btn btn-sm btn-light w-100 mt-1 text-muted">
            + Add card
        </button>
    </div>
</template>

<style>
.drag-ghost {
    opacity: 0.4;
    background: #c8ebfb;
    border-radius: 4px;
}
.drag-active {
    cursor: grabbing !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}
</style>
