<script setup>

import BoardListItem from "@/Layouts/Includes/BoardListItem.vue";
import Button from "@/Components/Common/Button.vue";
import { useRoutes } from "@/composables/useRoutes.js";
import { useBoard} from "@/composables/useBoard.js";

const routes = useRoutes()

const { currentBoard, boards } = useBoard();

</script>

<template>
    <div class="collapse collapse-horizontal show vh-100 bg-light pb-4 px-3" id="collapseWidthExample">
        <div class="list-group" style="width: 300px;">
            <Button variant="light" class="w-100 list-group-item list-group-item-action border-0" :href="routes.dashboard()">
                <i class="bi bi-card-list"></i> Dashboard
            </Button>
            <Button variant="light" class="w-100 list-group-item list-group-item-action border-0">
                <i class="bi bi-bell"></i> Notifications
            </Button>
            <Button variant="light" class="w-100 list-group-item list-group-item-action border-0" :href="routes.boards.edit()">
                <i class="bi bi-gear"></i> Board settings
            </Button>
            <Button variant="light" class="w-100 list-group-item list-group-item-action border-0" :href="routes.boards.create()">
                <i class="bi bi-clipboard-plus"></i> Create new board
            </Button>
        </div>
        <hr>
        <h5 class="ms-3">Board List:</h5>
        <div class="list-group" style="width: 300px;">
            <BoardListItem v-for="board in boards" :key="board.id" :board="board" class="border-0" :active="currentBoard ? board.id === currentBoard.id : false" />
        </div>
    </div>
</template>
