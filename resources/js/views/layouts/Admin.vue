<template>
    <div>
        <TheHeader />

        <TheSidebar />

        <div class="relative pl-64 pt-12 min-h-[calc(100vh-3rem)] bg-[#FAFAFA]">
            <div class="p-10">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import TheHeader from '../../components/admin/TheHeader.vue';
import TheSidebar from '../../components/admin/TheSidebar.vue';
</script>