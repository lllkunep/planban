<script setup>
import {Head, router} from '@inertiajs/vue3'
import {ref} from "vue";
import BoardLayout from '@/Layouts/BoardLayout.vue'
import General from "@/Pages/Board/Edit/Partial/General.vue";
import Members from "@/Pages/Board/Edit/Partial/Members.vue";
import Advanced from "@/Pages/Board/Edit/Partial/Advanced.vue";
import {useCan} from "@/composables/useCan.js";
import Button from "@/Components/Common/Button.vue";
import { useRoutes } from "@/composables/useRoutes.js";

const routes = useRoutes()

const props = defineProps({
    board: {
        type: Object,
        required: true,
    },
    roles: {
        type: Array,
        required: true,
    }
})

const board = ref({...props.board})

const can = useCan()

function detachMe(){
    if(!confirm('Are you sure you want to detach yourself from this board?')) return;

    router.delete(routes.boards.detachMe());
}

</script>

<template>
    <Head :title="'Settings: ' + board.name"/>
    <BoardLayout>
        <div class="container mt-5">
            <div class="row mb-5">
                <div class="col-12 py-5 px-4 border rounded shadow-sm">
                    <General v-model:board="board"/>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-12 py-5 px-4 border rounded shadow-sm">
                    <Members v-model:board="board" :roles="roles"/>
                </div>
            </div>
            <div v-if="can.owner" class="row mb-5">
                <div class="col-12 py-5 px-4 border rounded shadow-sm">
                    <Advanced v-model:board="board"/>
                </div>
            </div>
            <div v-else class="row mb-5">
                <div class="col-12 py-5 px-4 border rounded shadow-sm">
                    <h4 class="text-danger mt-5">
                        Detach me from this board
                    </h4>
                    <Button variant="danger" type="button" @click="detachMe">Detach me</Button>
                </div>
            </div>
        </div>
    </BoardLayout>
</template>
