<script setup>
import AppNotification from '@/components/admin/AppNotification.vue'
import { useNotificationsStore } from "@/stores/notifications";

const notificationsStore = useNotificationsStore()
</script>

<template>

    <div class="fixed bottom-10 right-10 z-10">
        <TransitionGroup name="list" tag="ul" class="grid gap-3">
            <AppNotification v-for="(notification, index) in notificationsStore.notifications" :key="index" :type="notification.type">
                {{ notification.message }}
            </AppNotification>
        </TransitionGroup>
    </div>
</template>

<style>
.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}
.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateX(30px);
}
</style>