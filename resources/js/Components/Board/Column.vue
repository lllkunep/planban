<script setup>
import {ref} from 'vue'
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

const emit = defineEmits(['card-selected', 'card-moved', 'column-added', 'column-deleted' ])

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

async function changeColumnName(event) {
    const newName = event.target.value
    if (!newName) return

    if (!props.column.id) {
        props.column.name = newName
        const { data } = await axios.put(route('columns.store'), props.column)
        props.column.id = data.id
        emit('column-added')
    } else {
        await axios.patch(route('columns.update', props.column.id), {
            name: newName,
        })
    }
}

async function deleteColumn(columnId) {
    await axios.delete(route('columns.destroy', columnId))
    emit('column-deleted', columnId)
}

</script>

<template>
    <div class="flex-shrink-0" style="width: 280px">
        <div class="d-flex justify-content-between align-items-center
                    bg-light rounded-top p-2 column-handle"
             style="cursor: grab">
            <UnborderedInput
                :value="column.name"
                @change="changeColumnName"
                @mousedown.stop
                :placeholder="column.id ? 'Column Name...' : '+ New Column'"
                required
            />
            <IconButton
                icon="trash"
                @mousedown.stop
                :class="{'disabled': localCards.length > 0}"
                :title="localCards.length > 0 ? 'Cannot delete non-empty column' : ''"
                v-if="column.id"
                @click="localCards.length === 0 && deleteColumn(column.id)"
                style="pointer-events: auto;cursor: not-allowed;"
            />
        </div>

        <AddCardForm v-if="column.id" :on-submit="submitAddCard"/>

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
