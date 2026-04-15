<script setup>
import FormField from "@/Components/Form/FormField.vue";
import Button from "@/Components/Common/Button.vue";
import { nextTick, ref } from "vue";

const props = defineProps({
    onSubmit: {
        type: Function,
        required: true,
    },
});

const addingCard = ref(false)
const newCardInput = ref(null)
const newCardName = ref('')

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
    await props.onSubmit(newCardName.value)
    cancelAddCard()
}

</script>

<template>
    <div v-if="addingCard" class="mt-1 p-1">
        <FormField
            ref="newCardInput"
            v-model="newCardName"
            class="form-control form-control-sm mb-1"
            placeholder="Card name..."
            @keydown.enter.prevent="submitAddCard"
            @keydown.esc="cancelAddCard"
        />
        <div class="d-flex gap-1">
            <Button variant="primary" @click="submitAddCard">
                Add
            </Button>
            <Button variant="light" @click="cancelAddCard">
                Cancel
            </Button>
        </div>
    </div>
    <Button v-else variant="light" class="w-100" @click="openAddCard"> + Add card </Button>
</template>

<style scoped>

</style>
