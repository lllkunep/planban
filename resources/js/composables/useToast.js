import { ref } from 'vue'

const toasts = ref([])

export function useToast() {
    function add(message, type = 'success', timeout = 3000) {
        const id = Date.now()

        toasts.value.push({ id, message, type })

        setTimeout(() => remove(id), timeout)
    }

    function remove(id) {
        toasts.value = toasts.value.filter(t => t.id !== id)
    }

    return {
        toasts,
        add,
        remove,
        success: (message, timeout) => add(message, 'success', timeout),
        error:   (message, timeout) => add(message, 'danger',  timeout),
        warning: (message, timeout) => add(message, 'warning', timeout),
        info:    (message, timeout) => add(message, 'info',    timeout),
    }
}
