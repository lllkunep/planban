<script setup>
import { ref, nextTick } from 'vue'
import draggable from 'vuedraggable'
import axios from 'axios'
import CardItem from '@/Components/Board/CardItem.vue'
import UnborderedInput from "@/Components/Form/UnborderedInput.vue";
import IconButton from "@/Components/Common/IconButton.vue";
import AddCardForm from "@/Components/Card/AddCardForm.vue";

const props = defineProps({
    column: {
        type: Object,
        required: true,
    },
})

const emit = defineEmits(['card-moved', 'card-selected'])

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

async function submitAddCard(newCardName) {
    const name = newCardName
    if (!name) return

    const { data } = await axios.post(route('cards.store'), {
        column_id: props.column.id,
        name,
    })

    localCards.value.push(data)
}
</script>

<template>
    <div class="flex-shrink-0" style="width: 280px">
        <div class="d-flex justify-content-between align-items-center
                    bg-light rounded-top p-2 column-handle"
             style="cursor: grab">
            <UnborderedInput
                :value="column.name"
                @mousedown.stop
            />
            <IconButton
                icon="trash"
                @mousedown.stop
            />
        </div>

        <AddCardForm :on-submit="submitAddCard"/>

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
                <CardItem :card="element" @card-selected="emit('card-selected', $event)" />
            </template>
        </draggable>
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
