<script setup>
import { ref, nextTick } from 'vue'
import axios from 'axios'
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

const addingCard = ref(false)
const newCardName = ref('')
const newCardInput = ref(null)

async function openAddCard() {
    addingCard.value = true
    await nextTick()
    newCardInput.value?.focus()
}

function cancelAddCard() {
    addingCard.value = false
    newCardName.value = ''
}

async function submitAddCard() {
    const name = newCardName.value.trim()
    if (!name) return

    const { data } = await axios.post(route('cards.store'), {
        column_id: props.column.id,
        name,
    })

    localCards.value.push(data)
    cancelAddCard()
}
</script>

<template>
    <div class="flex-shrink-0" style="width: 280px">

        <div class="d-flex justify-content-between align-items-center
                    bg-light rounded-top p-2 column-handle"
             style="cursor: grab">
            <input
                class="form-control form-control-sm border-0 bg-transparent fw-bold"
                :value="column.name"
                style="cursor: text"
                @mousedown.stop
            />
            <button class="btn btn-sm btn-link text-muted" @mousedown.stop>✕</button>
        </div>

        <div v-if="addingCard" class="mt-1 p-1">
            <textarea
                ref="newCardInput"
                v-model="newCardName"
                class="form-control form-control-sm mb-1"
                rows="2"
                placeholder="Card name..."
                @keydown.enter.prevent="submitAddCard"
                @keydown.esc="cancelAddCard"
            />
            <div class="d-flex gap-1">
                <button class="btn btn-sm btn-primary" @click="submitAddCard">Add</button>
                <button class="btn btn-sm btn-light" @click="cancelAddCard">✕</button>
            </div>
        </div>
        <button v-else class="btn btn-sm btn-light w-100 mt-1 text-muted" @click="openAddCard">
            + Add card
        </button>

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
