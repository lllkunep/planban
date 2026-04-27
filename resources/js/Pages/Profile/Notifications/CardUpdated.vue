<script setup>


import {onMounted, ref, useId} from "vue";

const collapseEl = ref(null)

const props = defineProps({
    notification: {
        type: Object,
        required: true,
    },
})

const emit = defineEmits(['read'])

onMounted(() => {
    collapseEl.value.addEventListener('shown.bs.collapse', function () {
        emit('read', props.notification.id)
    })
})

const id = useId()
</script>

<template>
    <div class="accordion-item" ref="collapseEl">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="`#panelsStayOpen-collapse${id}`" aria-expanded="false" :aria-controls="`panelsStayOpen-collapse${id}`">
                {{ notification.data.title }} <span class="ps-2"><i v-if="!(notification.read_at)" class="bi bi-circle-fill text-warning"></i></span>
            </button>
        </h2>
        <div :id="`panelsStayOpen-collapse${id}`" class="accordion-collapse collapse">
            <div class="accordion-body">
                <p>
                    Card <a target="_blank" :href="route('boards.onCard', [notification.data.card.board_id, notification.data.card.id])">{{notification.data.card.name}}</a> was updated <span v-if="notification.data.associated_user">by <b>{{notification.data.associated_user.name}}</b></span>.
                </p>
                <ul>
                    <li v-for="text in notification.data.texts" :key="text">
                        {{ text }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
