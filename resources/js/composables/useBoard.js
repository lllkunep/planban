import { computed } from 'vue'
import { usePage }  from '@inertiajs/vue3'

export function useBoard() {
    const page = usePage()

    const currentBoard = computed(() => page.props.currentBoard)
    const boards       = computed(() => page.props.boards)

    return {
        currentBoard,
        boards,
    }
}
