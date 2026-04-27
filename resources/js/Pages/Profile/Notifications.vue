<script setup>
import BoardLayout from '@/Layouts/BoardLayout.vue'
import {Head} from "@inertiajs/vue3";
import CardUpdated from "@/Pages/Profile/Notifications/CardUpdated.vue";

const components = {
    CardUpdated
}

const props = defineProps({
    notifications: {
        type: Array,
        required: true,
    },
})

async function read(id) {
    const {data} = await axios.patch(route('notifications.read', {
        notification: id
    }))

    if (data.success) {
        const index = props.notifications.findIndex(notification => notification.id === id)
        if (index !== -1) {
            props.notifications[index].read_at = data.data.read_at
        }
    }
}

</script>

<template>
    <BoardLayout>
        <Head title="Notifications" />
        <div class="container mt-5">
            <div class="row mb-5">
                <div v-if="notifications.length">
                    <h3>
                        Notifications
                    </h3>
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        <component
                            v-for="notification in notifications" :key="notification.id"
                            :is="components[notification.data.type]"
                            :notification="notification"
                            @read="read"
                        />
                    </div>
                </div>
                <div v-else>
                    <h3>
                        You don't have any notifications
                    </h3>
                </div>
            </div>
        </div>
    </BoardLayout>
</template>

<style scoped>

</style>
