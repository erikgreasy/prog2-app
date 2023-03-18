<script setup>
import { useUserNotificationsStore } from '@/stores/userNotifications'
import { onMounted } from 'vue'

const notificationsStore = useUserNotificationsStore()

onMounted(() => {
    notificationsStore.getNotifications()
})
</script>

<template>
    <div>
        <div v-for="notification in notificationsStore.notifications" :key="notification.id" class="fixed bottom-10 w-full">
            <div class="container">
                <div class="bg-red-200 relative p-5">
                    <span v-if="notification.type == 'App\\Notifications\\SubmissionProcessed'">
                        Vaše odovzdanie z {{ notification.data.submission.created_at }} bolo vyhodnotené.
                    </span>

                        <RouterLink
                            @click="notificationsStore.markAsRead(notification.id)"
                            :to="{
                                name: 'assignments.submissions.show', 
                                params: {
                                    slug: notification.data.submission.assignment?.slug,
                                    index: notification.data.submission.try
                                }
                            }"
                        >Zobrazit</RouterLink>
                    <!-- {{ notification.data.message }} -->

                    <button @click="notificationsStore.markAsRead(notification.id)">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>