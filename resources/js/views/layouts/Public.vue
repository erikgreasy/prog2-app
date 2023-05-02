<script setup>
import AppButton from '@/components/AppButton.vue';
import AppLogo from '@/components/AppLogo.vue';
import NavbarItem from '@/components/NavbarItem.vue';
import UserNotifications from '@/components/public/UserNotifications.vue';
import { useAuthStore } from '@/stores/auth.js'
import { useAuth } from '@/composables/auth';
import { ref, computed } from 'vue';
import { onClickOutside } from '@vueuse/core'

const authStore = useAuthStore()

const { logout, openLogin } = useAuth()

const navbarVisible = ref(false)
const myprofileDropdown = ref(null)

onClickOutside(myprofileDropdown, (event) => {
    myprofileDropdownOpen.value = false
})

const myprofileDropdownOpen = ref(false)

const isDesktop = computed(() => {
    return window.innerWidth >= 1024
})

const togglDarkMode = () => {
    alert('toggl dark mode')
}
</script>

<template>
    <div>
        <div
            v-if="authStore.loggedIn && (!authStore.user.vcs_username || !authStore.user.github_repo || !authStore.user.github_access_token)"
            class="bg-red-600 text-white py-2"
        >
            <div class="container">
                Pre dokončenie registrácie prosím <router-link :to="{name: 'myprofile.github'}" class="underline">prepojte svoj GitHub účet.</router-link>
            </div>
        </div>
        <div class="container py-5">
            <nav class="lg:flex">
                <div class="flex justify-between items-center">
                    <!-- Logo -->
                    <AppLogo />

                    <button @click="navbarVisible = !navbarVisible" class="lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none">
                        <path d="M5 8H13.75M5 12H19M10.25 16L19 16" stroke="#464455" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>

                <ul v-if="isDesktop || navbarVisible" class="lg:flex lg:items-center lg:ml-auto lg:gap-x-10">
                    <NavbarItem @clicked="navbarVisible = false" route-name="home">
                        Domov
                    </NavbarItem>

                    <NavbarItem @clicked="navbarVisible = false" route-name="assignments.index">
                        Zadania
                    </NavbarItem>

                    <AppButton v-if="!authStore.loggedIn" @click.prevent="openLogin" href="/login" size="small" raw>
                        Prihlásiť sa
                    </AppButton>

                    <!-- <NavbarItem v-else-if="authStore.user.role === 'admin' || authStore.user.role === 'teacher'" route-name="admin.dashboard" additionalClass="!text-primary">
                        {{ authStore.user.name }}
                    </NavbarItem> -->

                    <NavbarItem v-else class="relative" ref="myprofileDropdown">
                        <span @click="myprofileDropdownOpen = !myprofileDropdownOpen" class="flex items-center text-primary gap-x-2 h-full">
                            {{ authStore.user.name }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>

                        <div v-if="myprofileDropdownOpen" class="absolute bg-white px-8 py-4 rounded-lg bottom-2 translate-y-full right-0 shadow-lg z-10">
                            <ul class="whitespace-nowrap">
                                <li class="py-1">
                                    <router-link @click="myprofileDropdownOpen = false" :to="{name: 'myprofile'}" class="block py-1">
                                        Dashboard
                                    </router-link>
                                </li>
                                <li class="py-1">
                                    <router-link @click="myprofileDropdownOpen = false" :to="{name: 'myprofile.github'}" class="block py-1">
                                        GitHub prepojenie
                                    </router-link>
                                </li>
                                <li class="py-1 flex items-center gap-x-2 text-red-600" @click="logout">
                                    Logout
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </li>
                            </ul>
                        </div>
                    </NavbarItem>

                    <button @click="togglDarkMode" type="button">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 18.5C13.7239 18.5 15.3772 17.8152 16.5962 16.5962C17.8152 15.3772 18.5 13.7239 18.5 12C18.5 10.2761 17.8152 8.62279 16.5962 7.40381C15.3772 6.18482 13.7239 5.5 12 5.5C10.2761 5.5 8.62279 6.18482 7.40381 7.40381C6.18482 8.62279 5.5 10.2761 5.5 12C5.5 13.7239 6.18482 15.3772 7.40381 16.5962C8.62279 17.8152 10.2761 18.5 12 18.5V18.5Z" stroke="#718096" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M19.14 19.14L19.01 19.01M19.01 4.99L19.14 4.86L19.01 4.99ZM4.86 19.14L4.99 19.01L4.86 19.14ZM12 2.08V2V2.08ZM12 22V21.92V22ZM2.08 12H2H2.08ZM22 12H21.92H22ZM4.99 4.99L4.86 4.86L4.99 4.99Z" stroke="#718096" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </ul>
            </nav>
        </div>

        <main class="min-h-[calc(100vh-68px-60px)]">
            <slot></slot>
        </main>

        <footer>
            <div class="container text-center text-sliver py-5 text-sm">
                <div>
                    Copyright © 2022 Pavol Marák a <a href="https://greasy.dev" target="_blank" rel="noopener noreferrer">Erik Masný</a>, ÚIM FEI STU
                </div>
                <div>
                    Powered by Laravel, Vue.js and Tailwind CSS
                </div>
            </div>
        </footer>

        <UserNotifications />
    </div>
</template>
