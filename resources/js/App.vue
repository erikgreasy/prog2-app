<template>
    <component :is="$route.meta.layout || Public">
        <router-view v-slot="{Component}">
            <transition name="fade" mode="out-in">
                <component :is="Component" :key="$route.path"></component>
            </transition>
        </router-view>

    </component>
</template>

<script setup>
import Public from './views/layouts/Public.vue';
import { onBeforeMount } from '@vue/runtime-core';
import axios from 'axios';
import { useAuthStore } from './stores/auth';

const store = useAuthStore()

onBeforeMount(async () => {
    try {
        const res = await axios.get('/api/user')
        // console.log(res)
        store.user = res.data
        store.loggedIn = true
    } catch(err) {
        if(err.response.status == 401) {
            console.log('not logged in')
        }
    }
})
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition-duration: .2s;
  transition-property: opacity;
  transition-timing-function: ease-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0
}
</style>