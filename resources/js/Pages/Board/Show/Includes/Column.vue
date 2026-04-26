<script setup>
import {ref} from 'vue'
import draggable from 'vuedraggable'
import axios from 'axios'
import CardItem from '@/Pages/Board/Show/Includes/CardItem.vue'
import UnborderedInput from "@/Components/Form/UnborderedInput.vue";
import IconButton from "@/Components/Common/IconButton.vue";
import AddCardForm from "@/Pages/Board/Show/Includes/AddCardForm.vue";
import {useRoutes} from "@/composables/useRoutes.js";

const routes = useRoutes()
import {useToast} from '@/composables/useToast'

const toast = useToast()

const props = defineProps({
    column: {
        type: Object,
        required: true,
    },
})

const lastName = ref(props.column.name)

const emit = defineEmits(['card-move-start', 'card-selected', 'card-moved', 'column-added', 'column-deleted'])

function handleChange(event) {
    const item = event.added ?? event.moved
    if (!item) return

    const newIndex = item.newIndex
    emit('card-moved', props.column, item.element, newIndex);
}

async function submitAddCard(newCardName) {
    const name = newCardName
    if (!name) return

    try {
        const {data} = await axios.post(routes.boards.cards.store(props.column), {
            name: name,
        })

        props.column.cards.unshift(data.data)
    } catch (error) {
        const message = error.response?.data.message ?? 'Something went wrong';
        toast.error(message)
    }
}

async function changeColumnName(event) {
    const newName = event.target.value
    if (!newName) return

    try {
        if (!props.column.id) {
            props.column.name = newName
            const {data} = await axios.post(routes.boards.columns.store(), props.column)
            props.column.id = data.data.id
            emit('column-added')
        } else {
            await axios.patch(routes.boards.columns.update(props.column), {
                name: newName,
            })
        }
        lastName.value = props.column.name
    } catch (error) {
        props.column.name = lastName.value
        event.target.value = lastName.value
        const message = error.response?.data.message ?? 'Something went wrong';
        toast.error(message)
    }
}

async function deleteColumn(columnId) {
    try {
        await axios.delete(routes.boards.columns.destroy(props.column))
        emit('column-deleted', columnId)
    } catch (error) {
        const message = error.response?.data.message ?? 'Something went wrong';
        toast.error(message)
    }
}

</script>

<template>
    <div class="flex-shrink-0 overflow-auto" style="width: 400px; height: calc(100vh - 110px)">
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
                :class="{'disabled': props.column.cards.length > 0}"
                :title="props.column.cards.length > 0 ? 'Cannot delete non-empty column' : ''"
                v-if="column.id"
                @click="props.column.cards.length === 0 && deleteColumn(column.id)"
                :style="props.column.cards.length > 0 ? 'pointer-events: auto;cursor: not-allowed;' : ''"
            />
        </div>

        <AddCardForm v-if="column.id" :on-submit="submitAddCard"/>

        <draggable
            v-model="props.column.cards"
            group="cards"
            item-key="id"
            class="rounded-bottom p-2"
            style="min-height: 60px; background-color: #f8f9fa"
            ghost-class="drag-ghost"
            drag-class="drag-active"
            @change="handleChange"
            @start="emit('card-move-start')"
        >
            <template #item="{ element }">
                <CardItem :card="element" @card-selected="emit('card-selected', $event)"/>
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
