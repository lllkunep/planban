<script setup>
import OneLineTextForm from "@/Components/Form/OneLineTextForm.vue";
import axios from "axios";
import {ref} from "vue";
import IconButton from "@/Components/Common/IconButton.vue";
import { useRoutes } from "@/composables/useRoutes.js";
import { useAxiosForm } from "@/composables/useAxiosForm.js";

const routes = useRoutes()

const board = defineModel('board', {
    type: Object,
    required: true,
})

const props = defineProps({
    roles: {
        type: Array,
        required: true,
    },
})

const newMemberForm = useAxiosForm({
    email: '',
})

const memberAddedMessage = ref(null);

function addMember() {
    memberAddedMessage.value = null;

    newMemberForm.post(routes.boards.users.attach(), {
        onSuccess: (response) => {
            if (response.type === 'member') {
                props.board.members.push(response.data);
            } else if (response.type === 'invitation') {
                props.board.invitations.push(response.data);
            }
            newMemberForm.reset();
        }
    })
}

async function changeMemberRole(member, event) {
    const { data } = await axios.patch(routes.boards.users.changeRole(member), {
        role: event.target.value,
    });

    member.pivot.role = data.data.pivot.role;
}

async function removeMember(member) {
    if (!confirm(`Delete member "${member.name}"?`)) return

    const { data } = await axios.delete(routes.boards.users.detach(member));

    props.board.members = props.board.members.filter(m => m.id !== member.id);
}

async function removeInvitation(invitation) {
    if (!confirm(`Delete invitation "${invitation.email}"?`)) return

    const { data } = await axios.delete(routes.boards.removeInvitation(invitation));
    props.board.invitations = props.board.invitations.filter(i => i.id !== invitation.id);
}

</script>

<template>
    <h3>Manage members</h3>

    <h4>Add member</h4>
    <OneLineTextForm
        :form="newMemberForm"
        field="email"
        label="Email"
        buttonText="Add"
        @submit="addMember"
    />
    <div class="text-success" v-if="memberAddedMessage">
        {{ memberAddedMessage }}
    </div>

    <hr>

    <h4 class="mt-4">Members</h4>

    <ul class="list-group" style="max-width: 50rem;">
        <li class="list-group-item d-flex justify-content-between align-items-center" v-for="member in board.members">
            {{ member.name }} | {{ member.email }}
            <div class="actions d-flex w-50 gap-1">
                <select class="form-select" aria-label="Role" :disabled="member.pivot.role === 'owner'" @change="changeMemberRole(member, $event)">
                    <option :selected="member.pivot.role === role.value" v-for="role in roles" :key="role.value" :value="role.value" :disabled="role.value === 'owner'">
                        {{ role.label }}
                    </option>
                </select>
                <IconButton icon="trash" variant="danger" :disabled="member.pivot.role === 'owner'" @click="removeMember(member)" />
            </div>
        </li>
    </ul>

    <hr>

    <h4 class="mt-4">Invitations</h4>

    <ul class="list-group" style="max-width: 50rem;">
        <li class="list-group-item d-flex justify-content-between align-items-center" v-for="invitation in board.invitations">
            {{ invitation.email }}
            <div class="actions d-flex gap-1">
                <IconButton icon="trash" variant="danger" @click="removeInvitation(invitation)"/>
            </div>
        </li>
    </ul>

</template>

<style scoped>

</style>
