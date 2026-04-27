<script setup>
import OneLineTextForm from "@/Components/Form/OneLineTextForm.vue";
import axios from "axios";
import {ref} from "vue";
import IconButton from "@/Components/Common/IconButton.vue";
import {useRoutes} from "@/composables/useRoutes.js";
import {useAxiosForm} from "@/composables/useAxiosForm.js";
import ListItemWithActions from "@/Components/Form/ListItemWithActions.vue";
import {useCan} from "@/composables/useCan.js";

const can = useCan()

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

const membersError = ref('')
const invitationsError = ref('')

function addMember() {
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
    try {
        const {data} = await axios.patch(routes.boards.users.changeRole(member), {
            role: event.target.value,
        });

        member.pivot.role = data.data.pivot.role;
    } catch (error) {
        membersError.value = error.response?.data.message ?? 'Something went wrong';
    }
}

async function removeMember(member) {
    if (!confirm(`Delete member "${member.name}"?`)) return

    try {
        await axios.delete(routes.boards.users.detach(member));
        props.board.members = props.board.members.filter(m => m.id !== member.id);
    } catch (error) {
        membersError.value = error.response?.data.message ?? 'Something went wrong';
    }
}

async function removeInvitation(invitation) {
    if (!confirm(`Delete invitation "${invitation.email}"?`)) return

    try {
        await axios.delete(routes.boards.removeInvitation(invitation));
        props.board.invitations = props.board.invitations.filter(i => i.id !== invitation.id);
    } catch (error) {
        console.log(invitation)
        console.error(error);
        invitationsError.value = error.response?.data.message ?? 'Something went wrong';
    }
}

</script>

<template>
    <h3>Manage members</h3>

    <div v-if="can.admin">
        <h4>Add member</h4>
        <OneLineTextForm
            :form="newMemberForm"
            field="email"
            label="Email"
            buttonText="Add"
            @submit="addMember"
        />

        <hr>
    </div>

    <h4 class="mt-4">Members</h4>

    <div v-if="membersError" class="alert alert-danger" role="alert">
        {{ membersError }}
    </div>
    <ul class="list-group" style="max-width: 50rem;">
        <ListItemWithActions
            v-for="member in board.members"
        >
            {{ member.name }} | {{ member.email }}
            <template #actions>
                <select class="form-select" aria-label="Role" :disabled="member.pivot.role === 'owner' || !can.admin"
                        @change="changeMemberRole(member, $event)">
                    <option :selected="member.pivot.role === role.value" v-for="role in roles" :key="role.value"
                            :value="role.value" :disabled="role.value === 'owner'">
                        {{ role.label }}
                    </option>
                </select>
                <IconButton icon="trash" variant="danger" :disabled="member.pivot.role === 'owner' || !can.admin"
                            @click="removeMember(member)"/>
            </template>
        </ListItemWithActions>
    </ul>

    <hr>

    <h4 class="mt-4">Invitations</h4>

    <div v-if="invitationsError" class="alert alert-danger" role="alert">
        {{ invitationsError }}
    </div>
    <ul class="list-group" style="max-width: 50rem;">
        <ListItemWithActions v-for="invitation in board.invitations">
            {{ invitation.email }}
            <template #actions>
                <IconButton icon="trash" variant="danger" @click="removeInvitation(invitation)" :disabled="!can.admin"/>
            </template>
        </ListItemWithActions>
    </ul>

</template>

<style scoped>

</style>
