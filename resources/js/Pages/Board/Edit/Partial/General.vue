<script setup>

import TagList from "@/Pages/Board/Edit/Partial/Includes/TagList.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import OneLineTextForm from "@/Components/Form/OneLineTextForm.vue";
import {computed} from "vue";
import { useRoutes } from "@/composables/useRoutes.js";

const routes = useRoutes();

const page = usePage();

const success = computed(() => page.props.flash.success)

const board = defineModel('board', {
    type: Object,
    required: true,
})

const boardSettingsForm = useForm({
    name: board.value.name,
});

function save() {
    boardSettingsForm.patch(routes.boards.update(), {
        preserveState:  true,
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
    />
    <hr>
    <h3 class="mt-3">Tags</h3>
    <TagList
        v-model:board="board"
    />

</template>

<style scoped>

</style>
