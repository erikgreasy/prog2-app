<template>
    <div>
        <div class="container py-5">
            <nav class="flex">
                <!-- Logo -->
                <AppLogo />
                
                <ul class="flex items-center ml-auto gap-x-10">
                    <NavbarItem route-name="home">
                        Home
                    </NavbarItem>

                    <NavbarItem route-name="assignments.index">
                        Zadania
                    </NavbarItem>
                    
                    <AppButton v-if="!authStore.loggedIn" @click.prevent="openLogin" href="/login" size="small" raw>
                        Prihlásiť sa
                    </AppButton>

                    <NavbarItem v-else-if="authStore.user.role === 'admin' || authStore.user.role === 'teacher'" route-name="admin.dashboard">
                        {{ authStore.user.name }}
                    </NavbarItem>

                    <NavbarItem v-else route-name="myprofile">
                        {{ authStore.user.name }}
                    </NavbarItem>
                </ul>
            </nav>
        </div>

        <slot></slot>
    </div>
</template>

<script setup>
import AppButton from '../../components/AppButton.vue';
import AppLogo from '../../components/AppLogo.vue';
import NavbarItem from '../../components/NavbarItem.vue';
import { useAuthStore } from '@/stores/auth.js'
import { useAuth } from './../../composables/auth';

const authStore = useAuthStore()

const { logout, openLogin } = useAuth()
</script>
