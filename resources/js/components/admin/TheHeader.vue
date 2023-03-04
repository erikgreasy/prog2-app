<script setup>
import AppLogo from '@/components/AppLogo.vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import { useAuth } from '../../composables/auth';
import GlobalSearch from './GlobalSearch.vue';
import { Dropdown, ListGroup, ListGroupItem } from 'flowbite-vue'

const router = useRouter()

const authStore = useAuthStore()

const { logout } = useAuth()

</script>

<template>
    <header class="border-b border-gray-200 h-16 fixed top-0 w-full z-10 px-5 flex items-center justify-between bg-white">
        <AppLogo />

        <GlobalSearch />

        <div>
            <dropdown class="bg-none" placement="bottom" text="Bottom">
                <template #trigger>
                    <button class="text-sm font-semibold text-primary flex gap-x-1 items-center">
                        {{ authStore.user.name }}
                        <svg class="w-3.5" width="20" height="19" viewBox="0 0 20 19" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M16.7078 7.08545L11.4469 12.2471C10.8256 12.8567 9.80887 12.8567 9.18757 12.2471L3.92664 7.08545"
                                stroke="#4E56A6" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </template>

                <list-group>
                    <list-group-item>
                        <button @click="logout">Logout</button>
                    </list-group-item>
                </list-group>
            </dropdown>
        </div>
    </header>
</template>
