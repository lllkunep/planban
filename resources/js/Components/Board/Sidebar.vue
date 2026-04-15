<script setup>

import BoardListItem from "@/Components/Board/BoardListItem.vue";
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const boards = computed(() => page.props.boards)
const currentBoard = defineProps({
    boardId: Number,
})
</script>

<template>
    <div class="collapse collapse-horizontal show vh-100 bg-light py-4 px-3" id="collapseWidthExample">
        <div class="list-group" style="width: 300px;">
            <a href="#" class="list-group-item list-group-item-action border-0">
                Notifications
            </a>
            <hr>
            <a href="#" class="list-group-item list-group-item-action border-0">Add user to board</a>
            <hr>
            <h5 class="ms-3">Board List:</h5>
            <BoardListItem v-for="board in boards" :key="board.id" :board="board" class="border-0" :active="board.id === currentBoard.boardId" />
        </div>
    </div>
</template>
