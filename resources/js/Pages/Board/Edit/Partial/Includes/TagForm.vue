<script setup>
import Tag from "@/Components/Common/Tag.vue";
import IconButton from "@/Components/Common/IconButton.vue";
import { useForm } from "@inertiajs/vue3";

const tag = defineModel('tag', {
    type: Object,
    required: true,
})

const emits = defineEmits(['tag-added', 'tag-deleted']);

const form = useForm({
    name: tag.value.name,
    color: tag.value.color,
    board_id: tag.value.board_id,
})

async function add(){
    if (tag.value.id) return

    try {
        const {data} = await axios.post(route('tags.store'), form);
        if (data.errors) {
            form.setError(data.errors);
        } else {
            tag.value = { id: data.id, name: data.name, color: data.color, board_id: data.board_id };
            emits('tag-added');
        }
    } catch (error) {
        if (error.response?.status === 422) {
            form.setError('name',error.response.data.message);
        }
    }
}

function update(){
    if (!tag.value.id) return

    form.patch(route('tags.update', tag.value.id), {
        preserveState:  true,
        preserveScroll: true,
    })
}

async function destroy() {
    if (!confirm(`Delete tag "${tag.value.name}"?`)) return

    await axios.delete(route('tags.destroy', tag.value.id));

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
