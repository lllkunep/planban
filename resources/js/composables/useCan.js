import {computed} from 'vue'
import {usePage} from '@inertiajs/vue3'

export function useCan() {
    const page = usePage()

    return computed(() => page.props.can)
}
