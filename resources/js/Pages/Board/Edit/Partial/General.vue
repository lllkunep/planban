<script setup>

import TagList from "@/Pages/Board/Edit/Partial/Includes/TagList.vue";
import {useForm} from "@inertiajs/vue3";
import OneLineTextForm from "@/Components/Form/OneLineTextForm.vue";
import {useRoutes} from "@/composables/useRoutes.js";
import {useCan} from "@/composables/useCan.js";

const routes = useRoutes();

const board = defineModel('board', {
    type: Object,
    required: true,
})

const boardSettingsForm = useForm({
    name: board.value.name,
});

const can = useCan(board.value)

function save() {
    boardSettingsForm.patch(routes.boards.update(), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            board.value.name = boardSettingsForm.name
        }
    })
}

</script>

<template>
    <h3>
        Board Settings
    </h3>
    <OneLineTextForm
        :form="boardSettingsForm"
        field="name"
        label="Board title"
        buttonText="Save"
        @submit="save"
        :disabled="!can.owner"
    />
    <hr>
    <h3 class="mt-3">Tags</h3>
    <TagList
        v-model:board="board"
    />

</template>

<style scoped>

</style>
