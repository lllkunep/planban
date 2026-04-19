import {ref, reactive} from 'vue'
import axios from 'axios'

export function useAxiosForm(initialData = {}) {
    const data = reactive({...initialData})
    const errors = ref({})
    const processing = ref(false)
    const wasSuccess = ref(false)

    function reset() {
        Object.assign(data, initialData)
        errors.value = {}
        wasSuccess.value = false
    }

    function clearErrors(...fields) {
        if (fields.length === 0) {
            errors.value = {}
            return
        }
        fields.forEach(field => delete errors.value[field])
    }

    function setError(field, message) {
        errors.value[field] = [message]
    }

    async function submit(method, url, options = {}) {
        processing.value = true
        wasSuccess.value = false
        errors.value = {}

        try {
            const response = await axios[method](url, data)

            wasSuccess.value = true

            if (options.onSuccess) {
                options.onSuccess(response)
            }

            return response

        } catch (error) {
            if (error.response?.status === 422) {
                errors.value = error.response.data.errors ?? {}
            }

            if (options.onError) {
                options.onError(error.response?.data)
            }

            throw error

        } finally {
            processing.value = false

            if (options.onFinish) {
                options.onFinish()
            }
        }
    }

    return {
        data,
        errors,
        processing,
        wasSuccess,

        reset,
        clearErrors,
        setError,

        get: (url, options) => submit('get', url, options),
        post: (url, options) => submit('post', url, options),
        patch: (url, options) => submit('patch', url, options),
        put: (url, options) => submit('put', url, options),
        delete: (url, options) => submit('delete', url, options),
    }
}
