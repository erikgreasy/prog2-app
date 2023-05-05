<script setup>
import Public from '@/views/layouts/Public.vue';
import {useDarkModeStore} from "@/stores/darkMode";

const darkModeStore = useDarkModeStore()
</script>

<template>
    <div :class="{dark: darkModeStore.darkMode}">
        <component :is="$route.meta.layout || Public">
            <router-view v-slot="{Component}">
                <transition name="fade" mode="out-in">
                    <component :is="Component" :key="$route.path"></component>
                </transition>
            </router-view>

        </component>
    </div>
</template>

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
