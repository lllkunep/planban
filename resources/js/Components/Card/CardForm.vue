<script setup>

import Textarea from "@/Components/Form/Textarea.vue";
import {useForm, usePage} from '@inertiajs/vue3'
import { computed } from 'vue'
import FormField from "@/Components/Form/FormField.vue";
import FormSelect from "@/Components/Form/FormSelect.vue";
import Multiselect from "@/Components/Form/Multiselect.vue";

const page = usePage()

const currentBoard = computed(() => page.props.currentBoard)

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
})

const cardForm = useForm({
    assigned_user_id: props.card.assigned_user_id,
    name: props.card.name,
    text: props.card.text,
    tags: props.card.tags,
});

function save() {
    cardForm.transform(data => ({
        ...data,
        tags: data.tags.map(tag => tag.id)
    })).patch(route('cards.update', props.card.id), {
        preserveState:  true,
        preserveScroll: true
    })
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
            :options="[{id:null, name:'None'}].concat(currentBoard.boardMembers)"
            :message="cardForm.errors.assigned_user_id"
        />
        <div class="row ps-3 g-3">
            <div class="col-auto">
                <label class="col-form-label" for="tags">Tags</label>
            </div>
            <div class="col-10">
                <Multiselect
                    id="tags"
                    :options="currentBoard.boardTags"
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
