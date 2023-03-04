<script setup>
import AppButton from '@/components/AppButton.vue';
</script>

<template>
    <div class="flex items-center justify-center text-center h-screen">
        <div>
            <div class="font-extrabold text-8xl mb-10">
                404
            </div>
            <AppButton :to="{name: 'home'}">Domov</AppButton>
        </div>
    </div>
</template>
