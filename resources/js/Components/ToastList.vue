<script setup>
import { useToast } from '@/composables/useToast'

const { toasts, remove } = useToast()
</script>

<template>
    <div
        class="toast-container position-fixed bottom-0 end-0 p-3"
        style="z-index: 9999"
    >
        <div
            v-for="toast in toasts"
            :key="toast.id"
            class="toast show align-items-center border-0"
            :class="`text-bg-${toast.type}`"
            role="alert"
        >
            <div class="d-flex">
                <div class="toast-body">
                    {{ toast.message }}
                </div>
                <button
                    type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    @click="remove(toast.id)"
                />
            </div>
        </div>
    </div>
</template>
