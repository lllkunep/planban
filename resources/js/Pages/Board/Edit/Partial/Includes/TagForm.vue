<script setup>
import Tag from "@/Components/Common/Tag.vue";
import IconButton from "@/Components/Common/IconButton.vue";
import { useRoutes } from "@/composables/useRoutes.js";
import { useAxiosForm } from "@/composables/useAxiosForm.js";

const routes = useRoutes()

const tag = defineModel('tag', {
    type: Object,
    required: true,
})

const emits = defineEmits(['tag-added', 'tag-deleted']);

const form = useAxiosForm({
    name: tag.value.name,
    color: tag.value.color,
    board_id: tag.value.board_id,
})

function add(){
    if (tag.value.id) return

    form.post(routes.boards.tags.store(), {
        onSuccess: (response) => {
            const data = response.data;
            tag.value = { id: data.id, name: data.name, color: data.color, board_id: data.board_id };
            emits('tag-added');
        }
    });
}

function update(){
    if (!tag.value.id) return

    form.patch(routes.boards.tags.update(tag.value));
}

async function destroy() {
    if (!confirm(`Delete tag "${tag.value.name}"?`)) return

    await axios.delete(routes.boards.tags.destroy(tag.value));

    emits('tag-deleted', tag.value.id);
}

</script>

<template>
    <form class="d-flex gap-3 align-items-center" @submit.prevent="add">
        <input type="color" class="form-control form-control-color" v-model="form.color" @change="update">
        <Tag
            :color="form.color"
            v-model:text="form.name"
            :placeholder="tag.id ? 'Tag name...' : 'New tag...'"
            @change="update"
            as-input
        />
        <IconButton v-if="tag.id" class="ms-auto" icon="trash" variant="danger" type="button" @click="destroy" />
        <IconButton v-else class="ms-auto" icon="plus" variant="primary" />
    </form>
    <div class="text-danger" v-for="error in form.errors"> {{ error }}</div>
</template>

<style scoped>

</style>
