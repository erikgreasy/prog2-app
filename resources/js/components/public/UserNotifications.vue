<script setup>
import { useUserNotificationsStore } from '@/stores/userNotifications'
import { onMounted } from 'vue'
import AppButton from '../AppButton.vue';

const notificationsStore = useUserNotificationsStore()

onMounted(() => {
    notificationsStore.getNotifications()
})
</script>

<template>
    <div>
        <Transition
            enter-active-class="duration-300 transition"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="notificationsStore.notifications.length">
                <div v-for="notification in notificationsStore.notifications" :key="notification.id" class="fixed bottom-10 w-full">
                    <div class="container">
                        <div class="shadow-[4px_10px_25px_2px_rgb(0_0_0_/_0.3)] border-t-2 border-primaryDark bg-white relative p-5">
                            <div v-if="notification.type == 'App\\Notifications\\SubmissionProcessed'">
                                <h4 class="font-bold mb-3">Odovzdanie vyhodnotené</h4>
                                <div class="mb-4">
                                    Vaše odovzdanie zadania "{{ notification.data.assignment.title }}" z {{ notification.data.submission.created_at.readable }} bolo vyhodnotené.
                                </div>
    
                                <AppButton
                                    size="small"
                                    @click="notificationsStore.markAsRead(notification.id)"
                                    :to="{
                                        name: 'assignments.submissions.show', 
                                        params: {
                                            slug: notification.data.assignment?.slug,
                                            index: notification.data.submission.try
                                        }
                                    }"
                                >Zobrazit</AppButton>
                            </div>
                            <!-- {{ notification.data.message }} -->
    
                            <button @click="notificationsStore.markAsRead(notification.id)" class="absolute top-5 right-5">
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16.3886 1.08225L15.3062 0L8.19429 7.11195L1.08234 0L0 1.08225L7.112 8.19424L0 15.3062L1.08234 16.3885L8.19429 9.27654L15.3062 16.3885L16.3886 15.3062L9.27659 8.19424L16.3886 1.08225Z" fill="black"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>