<script setup>

import Button from "@/Components/Common/Button.vue";
import Textarea from "@/Components/Form/Textarea.vue";
import {useAxiosForm} from "@/composables/useAxiosForm.js";
import {useRoutes} from "@/composables/useRoutes.js";

const routes = useRoutes();

const props = defineProps({
    card: {
        type: Object,
        required: true,
    },
})

const comment = defineModel('comment', {
    type: Object,
    required: false,
    default: () => ({
        text: '',
    }),
})

const emit = defineEmits(['close'])

const commentForm = useAxiosForm({
    text: comment.value.text,
})

function submit() {
    if(comment.value.id){
        commentForm.patch(routes.boards.cards.comments.update(props.card, comment.value), {
            onSuccess: (data) => {
                comment.value = data.data;
                emit('close');
            },
        })
    } else {
        commentForm.post(routes.boards.cards.comments.store(props.card), {
            onSuccess: (data) => {
                comment.value = data.data;
                commentForm.reset();
                emit('close');
            }
        })
    }
}
</script>

<template>
    <form @submit.prevent="submit">
        <div class="mb-3">
            <Textarea
                id="text"
                placeholder="Comment"
                height="150px"
                v-model="commentForm.text"
                :class="commentForm.errors.text ? 'is-invalid' : ''"
            />
            <div class="text-danger" v-if="commentForm.errors.text">{{ commentForm.errors.text }}</div>
            <div class="text-danger" v-else-if="commentForm.errorMessage">{{ commentForm.errorMessage }}</div>
            <div class="text-success" v-if="commentForm.successMessage">{{ commentForm.successMessage }}</div>
        </div>
        <Button variant="primary">Add comment</Button>
        <Button v-if="comment.id" variant="light" class="ms-2" @click="emit('close')">Cancel</Button>
    </form>
</template>

<style scoped>

</style>
