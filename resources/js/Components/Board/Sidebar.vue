<script setup>

import BoardListItem from "@/Components/Board/BoardListItem.vue";
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Button from "@/Components/Common/Button.vue";

const page = usePage()

const boards = computed(() => page.props.boards)
const currentBoard = defineProps({
    boardId: Number,
})
</script>

<template>
    <div class="collapse collapse-horizontal show vh-100 bg-light py-4 px-3" id="collapseWidthExample">
        <div class="list-group" style="width: 300px;">
            <Button variant="light" class="w-100 list-group-item list-group-item-action border-0">
                <i class="bi bi-bell"></i> Notifications
            </Button>
            <Button variant="light" class="w-100 list-group-item list-group-item-action border-0">
                <i class="bi bi-person-add"></i> Add user to board
            </Button>
            <Button variant="light" class="w-100 list-group-item list-group-item-action border-0" :href="route('boards.edit', currentBoard.boardId)">
                <i class="bi bi-gear"></i> Board settings
            </Button>
            <Button variant="light" class="w-100 list-group-item list-group-item-action border-0" :href="route('boards.edit', currentBoard.boardId)">
                <i class="bi bi-clipboard-plus"></i> Create new board
            </Button>
        </div>
        <hr>
        <h5 class="ms-3">Board List:</h5>
        <div class="list-group" style="width: 300px;">
            <BoardListItem v-for="board in boards" :key="board.id" :board="board" class="border-0" :active="board.id === currentBoard.boardId" />
        </div>
    </div>
</template>
