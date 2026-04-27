<script setup>
import Button from "@/Components/Common/Button.vue";
import axios from 'axios';
import {useRoutes} from "@/composables/useRoutes.js";
import {useToast} from "@/composables/useToast.js";

const routes = useRoutes();
const toast = useToast();

const props = defineProps({
    comment: {
        type: Object,
        required: true,
    }
})

const emit = defineEmits(['edit', 'deleted'])

async function deleteComment(){
    try{
        await axios.delete(routes.boards.cards.comments.destroy(props.comment.card_id, props.comment.id));
        emit('deleted', props.comment.id);
    } catch (error) {
        toast.error('Failed to delete comment');
    }
}

</script>

<template>
    <div
        :key="comment.id"
        class="p-2 mb-1 mb-3"
    >
        <h5>{{ comment.user?.name }}</h5>
        <p class="mb-0 text-muted">{{ comment.text }}</p>
        <Button v-if="comment.canUpdate" variant="link" class="me-3 p-0 text-decoration-none" size="sm" @click="$emit('edit')">Edit</Button>
        <Button v-if="comment.canDelete" variant="link" class="p-0 text-decoration-none text-danger" size="sm" @click="deleteComment">Delete</Button>
    </div>
</template>

<style scoped>

</style>
