<template>
    <div>
        <TheHeader />

        <TheSidebar />

        <div class="relative pl-64 pt-16 min-h-[calc(100vh)] bg-[#FAFAFA]">
            <div class="p-10">
                <slot></slot>
            </div>
        </div>

        <AppNotifications />
    </div>
</template>

<script setup>
import AppNotification from '@/components/admin/AppNotification.vue';
import AppNotifications from '@/components/admin/AppNotifications.vue';
import TheHeader from '@/components/admin/TheHeader.vue';
import TheSidebar from '@/components/admin/TheSidebar.vue';
</script>