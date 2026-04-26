<script setup>

import Textarea from "@/Components/Form/Textarea.vue";
import FormField from "@/Components/Form/FormField.vue";
import FormSelect from "@/Components/Form/FormSelect.vue";
import TagSelector from "@/Pages/Card/Includes/TagSelector.vue";
import { useAxiosForm } from "@/composables/useAxiosForm.js";
import { useBoard } from "@/composables/useBoard.js";
import { useRoutes } from "@/composables/useRoutes.js";

const { currentBoard } = useBoard()
const routes = useRoutes()

const card = defineModel('card', {
    type: Object,
    required: true,
})

const cardForm = useAxiosForm({
    assigned_user_id: card.value.assigned_user_id,
    name: card.value.name,
    text: card.value.text,
    tags: card.value.tags,
});

function save() {
    cardForm.patch(routes.boards.cards.update(card.value), {
        onSuccess: (data) => {
            Object.assign(card.value, data.data)
        },
    });
}

defineExpose({ save })

</script>

<template>
    <form id="card-form">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <FormField
                class="form-control form-control-lg bg-transparent fw-bold"
                id="name"
                v-model="cardForm.name"
                required
                autocomplete="name"
                :message="cardForm.errors.name"
            />
        </div>
        <FormSelect
            v-model="cardForm.assigned_user_id"
            id="assigned_user_id"
            label="Assigned user"
            :options="[{id:null, name:'None'}].concat(currentBoard.members)"
            :message="cardForm.errors.assigned_user_id"
        />
        <div class="row ps-3 g-3">
            <div class="col-auto">
                <label class="col-form-label" for="tags">Tags</label>
            </div>
            <div class="col-10">
                <TagSelector
                    id="tags"
                    :options="currentBoard.tags"
                    v-model="cardForm.tags"
                    track-by="id"
                    label="name"
                    placeholder="Select tags"
                />
            </div>
        </div>

        <div class="mb-3">
            <Textarea
                id="text"
                placeholder="Description"
                v-model="cardForm.text"
            />
        </div>
    </form>
</template>

<style scoped>

</style>
