<template>
    <nav class="flex">
        <ul class="flex">
            <li>
                <router-link :to="{name: 'home'}">Home</router-link>
            </li>
            <li>
                <router-link :to="{name: 'about'}">About</router-link>
            </li>
        </ul>
        <ul v-if="store.loggedIn" class="ml-auto flex">
            <li>
                {{ store.user.name }}
            </li>
            <li>
                <a href="/logout">Logout</a>
            </li>
        </ul>
        <ul v-else class="ml-auto flex">
            <li>
                <router-link :to="{name: 'login'}">Login</router-link>
            </li>
        </ul>
    </nav>

    <router-view />
</template>

<script setup>
import { onBeforeMount } from '@vue/runtime-core';
import axios from 'axios';
import { useAuthStore } from './stores/auth';

const store = useAuthStore()

onBeforeMount(async () => {
    try {
        const res = await axios.get('/api/user')
        console.log(res)
        store.user = res.data
        store.loggedIn = true
    } catch(err) {
        if(err.response.status == 401) {
            console.log('not logged in')
        }
    }
})
</script>
