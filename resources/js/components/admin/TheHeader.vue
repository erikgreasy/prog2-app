<template>
    <header class="border-b border-gray-200 h-12 fixed top-0 w-full z-10 px-5 flex items-center justify-between bg-white">
        <AppLogo />

        <button @click="logout">Logout</button>
    </header>
</template>

<script setup>
import AppLogo from '@/components/AppLogo.vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../../composables/auth';

const router = useRouter()

const { logout } = useAuth()

</script>