import { reactive } from 'vue'
import axios from 'axios'

export function useAxiosForm(initialData = {}) {
    const defaults = { ...initialData }

    const form = reactive({
        ...initialData,
        errors: {},
        successMessage: '',
        errorMessage: '',
        processing: false,
        wasSuccess: false,
    })

    function getData() {
        return Object.fromEntries(
            Object.keys(defaults).map(key => [key, form[key]])
        )
    }

    form.reset = () => {
        Object.assign(form, defaults)
        form.errors = {}
        form.wasSuccess = false
    }

    form.clearErrors = (...keys) => {
        if (keys.length === 0) { form.errors = {}; return }
        keys.forEach(key => delete form.errors[key])
    }

    form.setError = (field, message) => {
        form.errors[field] = [message]
    }

    async function submit(method, url, options = {}) {
        form.processing = true
        form.wasSuccess = false
        form.errors = {}

        try {
            const response = await axios[method](url, getData())
            form.wasSuccess = true
            form.successMessage = response.data.message ?? ''
            if (options.onSuccess) options.onSuccess(response.data)
            return response.data
        } catch (error) {
            if (error.response?.status === 422) {
                form.errors = error.response.data.errors ?? {}
            } else {
                form.errorMessage = error.response?.data.message ?? 'Something went wrong'
            }
            if (options.onError) options.onError(error.response?.data)
        } finally {
            form.processing = false
            if (options.onFinish) options.onFinish()
        }
    }

    form.get    = (url, opts) => submit('get',    url, opts)
    form.post   = (url, opts) => submit('post',   url, opts)
    form.patch  = (url, opts) => submit('patch',  url, opts)
    form.put    = (url, opts) => submit('put',    url, opts)
    form.delete = (url, opts) => submit('delete', url, opts)

    return form
}
