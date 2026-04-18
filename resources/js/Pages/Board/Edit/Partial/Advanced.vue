<script setup>

import OneLineDropdownForm from "@/Components/Form/OneLineDropdownForm.vue";
import Button from "@/Components/Common/Button.vue";
import {router, useForm} from "@inertiajs/vue3";
import axios from "axios";

const board = defineModel('board', {
    type: Object,
    required: true,
})

const form = useForm({
    user_id: board.value.members.find(m => m.pivot.role === 'owner')?.id,
})

async function setNewOwner() {
    if(!confirm('Are you sure you want to change owner?')) return;

    const {data} = await axios.patch(route('boards.setNewOwner', board.value.id), {
        user_id: form.user_id
    });
    board.value.members.find(m => m.pivot.role === 'owner').pivot.role = 'admin';
    board.value.members.find(m => m.id === form.user_id).pivot.role = 'owner';
}

function deleteBoard(){
    if(!confirm('Are you sure you want to delete this board?')) return;

    router.delete(route('boards.destroy', board.id))
}
</script>

<template>
    <h4>Set new owner</h4>
    <OneLineDropdownForm
        v-model:form="form"
        field="user_id"
        :options="board.members"
        label="User"
        track_by="id"
        label_by="name"
        buttonText="Change"
        @submit="setNewOwner"
    />
    <h4 class="text-danger mt-5">
        Delete board
    </h4>
    <Button variant="danger" type="button" @click="deleteBoard">! Delete board !</Button>
</template>

<style scoped>

</style>
