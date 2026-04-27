<script setup>
import {nextTick, onMounted} from "vue";
import TagForm from "./TagForm.vue";
import {useCan} from "@/composables/useCan.js";

const can = useCan()

const board = defineModel('board', {
    type: Object,
    required: true,
})

onMounted(() => {
    board.value.tags = [{'id': null, 'name': '', 'color': '#dddddd', 'board_id': board.value.id}, ...board.value.tags];
});

async function tagAdded() {
    await nextTick();
    board.value.tags = [{'id': null, 'name': '', 'color': '#dddddd', 'board_id': board.value.id}, ...board.value.tags]
}

function tagDeleted(tagId) {
    board.value.tags = board.value.tags.filter(t => t.id !== tagId)
}

</script>

<template>
    <ul class="list-group" style="max-width: 400px">
        <li
            class="list-group-item"
            v-for="(tag, index) in board.tags"
            :key="tag.id ?? 'new'"
        >
            <TagForm
                v-model:tag="board.tags[index]"
                @tag-added="tagAdded"
                @tag-deleted="tagDeleted"
                :disabled="!can.admin"
            />
        </li>
    </ul>
</template>

<style scoped>

</style>
