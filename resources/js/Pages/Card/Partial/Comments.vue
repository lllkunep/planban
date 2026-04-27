<script setup>
import Comment from "@/Pages/Card/Includes/Comment.vue";
import CommentForm from "@/Pages/Card/Includes/CommentForm.vue";
import {ref} from "vue";

const card = defineModel('card', {
    type: Object,
    required: true,
})

const editStates = ref(Object.fromEntries(card.value.comments.map(item => [item.id, false])));

function deleted(comment_id){
    card.value.comments = card.value.comments.filter(comment => comment.id !== comment_id);
}

function added(comment){
    editStates.value[comment.id] = false;
    card.value.comments = [...card.value.comments, comment];
}

</script>

<template>
    <div v-if="card.comments" class="mb-3">
        <h4>Comments</h4>
        <div v-for="(comment, index) in card.comments" :key="comment.id">
            <CommentForm v-if="editStates[comment.id]" v-model:comment="card.comments[index]" :card="card" @close="editStates[comment.id] = false"/>
            <Comment v-else :comment="comment" @edit="editStates[comment.id] = true" @deleted="deleted"/>
        </div>
    </div>

    <br>
    <h4>New comment</h4>
    <CommentForm :card="card" @added="added"/>
</template>

<style scoped>

</style>
