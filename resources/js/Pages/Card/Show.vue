<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import Comment from "@/Pages/Card/Includes/Comment.vue";
import NewCommentForm from "@/Pages/Card/Includes/NewCommentForm.vue";
import History from "@/Pages/Card/Includes/History.vue";
import CardForm from "@/Pages/Card/Partial/CardForm.vue";
import Button from "@/Components/Common/Button.vue";

const emit = defineEmits(['close']);

const card = defineModel('card', {
    type: Object,
    default: null,
})

const props = defineProps({
    loading: {
        type: Boolean,
        default: false,
    },
})
function onKeydown(e) {
    if (e.key === 'Escape') emit('close')
}

onMounted(() => window.addEventListener('keydown', onKeydown))
onUnmounted(() => window.removeEventListener('keydown', onKeydown))

const cardForm = ref(null)
</script>

<template>
    <Transition name="sidebar">
        <div v-if="card" class="sidebar-overlay" @click.self="emit('close')">
            <div class="sidebar-panel">
                <div class="float-end d-inline">
                    <Button variant="danger" class="me-2" @click="emit('close')">
                        Cancel
                    </Button>
                    <Button variant="success" @click="cardForm.save()">
                        Save
                    </Button>
                </div>
                <div v-if="loading" class="text-center text-muted py-4">
                    <div class="spinner-border spinner-border-sm" />
                </div>

                <template v-else>
                    <CardForm :card="card" ref="cardForm"/>
                    <hr>
                    <div v-if="card.comments?.length" class="mb-3">
                        <h4>Comments</h4>
                        <Comment v-for="comment in card.comments" :key="comment.id" :comment="comment" />
                    </div>

                    <br>
                    <NewCommentForm />

                    <div v-if="card.histories?.length" class="my-3">
                        <hr>
                        <label class="form-label fw-semibold text-muted">History</label>
                        <History v-for="history in card.histories" :key="history.id" :history="history" />
                    </div>
                </template>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.sidebar-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.3);
    z-index: 1000;
    display: flex;
    justify-content: flex-end;
}

.sidebar-panel {
    width: 600px;
    height: 100%;
    background: #fff;
    padding: 1.5rem;
    overflow-y: auto;
    box-shadow: -4px 0 16px rgba(0, 0, 0, 0.1);
}

.sidebar-enter-active,
.sidebar-leave-active {
    transition: opacity 0.2s ease;
}

.sidebar-enter-active .sidebar-panel,
.sidebar-leave-active .sidebar-panel {
    transition: transform 0.2s ease;
}

.sidebar-enter-from,
.sidebar-leave-to {
    opacity: 0;
}

.sidebar-enter-from .sidebar-panel,
.sidebar-leave-to .sidebar-panel {
    transform: translateX(100%);
}
</style>
