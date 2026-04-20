<script setup>
import BoardLayout from '@/Layouts/BoardLayout.vue'
import OneLineTextForm from "@/Components/Form/OneLineTextForm.vue";
import {Head} from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    name: '',
});

function create() {
    form.post(route('boards.store'))
}

</script>

<template>
    <BoardLayout>
        <Head title="Create new board" />
        <div class="container mt-5">
            <div class="row mb-5">
                <div class="col-12 py-5 px-4 border rounded shadow-sm">
                    <h3>
                        Create new board
                    </h3>
                    <OneLineTextForm
                        :form="form"
                        field="name"
                        label="Board title"
                        buttonText="Create"
                        @submit="create"
                    />
                </div>
            </div>
        </div>
    </BoardLayout>
</template>

<style scoped>

</style>
