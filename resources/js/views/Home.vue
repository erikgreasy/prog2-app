<template>
    <div>
        Hello {{ store.user?.name }}
    </div>
</template>

<script setup>
import { onMounted } from '@vue/runtime-core';
import axios from 'axios';
import { useAuthStore } from './../stores/auth';

const store = useAuthStore()

onMounted(async () => {
    const res = await axios.get('/api/user')
    store.user = res.data
})
</script>

<style>

</style>