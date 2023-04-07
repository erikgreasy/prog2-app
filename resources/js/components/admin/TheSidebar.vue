<script setup>
import { useAuth } from '@/composables/auth';
import { useAuthStore } from '@/stores/auth';
import AdminNavItem from '../../components/AdminNavItem.vue';
import { role } from '@/enums/role';

const authStore = useAuthStore()
</script>

<template>
    <aside class="fixed z-10 top-16 left-0 w-64 h-[calc(100%-2.5rem)]" aria-label="Sidenav">
        <div
            class="overflow-y-auto py-5 px-3 h-full bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <ul class="space-y-2">
                <AdminNavItem route-name="admin.dashboard">
                    <template #icon>
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11 16.5002V13.7502M9.23083 2.58519L2.87833 7.67269C2.16333 8.24102 1.70499 9.44185 1.86083 10.3402L3.07999 17.6369C3.29999 18.9385 4.54666 19.9927 5.86666 19.9927H16.1333C17.4442 19.9927 18.7 18.9294 18.92 17.6369L20.1392 10.3402C20.2858 9.44185 19.8275 8.24102 19.1217 7.67269L12.7692 2.59435C11.7883 1.80602 10.2025 1.80602 9.23083 2.58519V2.58519Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </template>
                    Dashboard
                </AdminNavItem>

                <AdminNavItem route-name="admin.assignments.index">
                    <template #icon>
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.5142 5.50016H15.0975M10.5142 16.5002H14.1808M10.5142 12.7877L15.0975 12.8335M10.5142 9.16683H13.2642M7.73667 6.41683H2.31917M19.6808 17.4168V4.5835C19.6808 2.75016 18.7642 1.8335 16.9308 1.8335H13.2642C11.4308 1.8335 10.5142 2.75016 10.5142 4.5835V17.4168C10.5142 19.2502 11.4308 20.1668 13.2642 20.1668H16.9308C18.7642 20.1668 19.6808 19.2502 19.6808 17.4168ZM5.0325 1.8335C3.53833 1.8335 2.31917 3.05266 2.31917 4.53766V16.4177C2.31917 16.8302 2.49333 17.4535 2.70417 17.811L3.45583 19.0577C4.3175 20.4968 5.73833 20.4968 6.6 19.0577L7.35167 17.811C7.5625 17.4535 7.73667 16.8302 7.73667 16.4177V4.53766C7.73667 3.05266 6.5175 1.8335 5.0325 1.8335Z" stroke="black" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </template>
                    Zadania
                </AdminNavItem>
               
                <AdminNavItem route-name="admin.students.index">
                    Študenti
                </AdminNavItem>

                <AdminNavItem v-if="authStore.user.role === role.ADMIN" route-name="admin.users.index">
                    <template #icon>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.41 4.00023C18.35 4.00023 19.91 5.57023 19.91 7.50023C19.91 9.39023 18.41 10.9302 16.54 11.0002C16.4536 10.9902 16.3664 10.9902 16.28 11.0002M18.34 20.0002C19.06 19.8502 19.74 19.5602 20.3 19.1302C21.86 17.9602 21.86 16.0302 20.3 14.8602C19.75 14.4402 19.08 14.1602 18.37 14.0002M9.16 10.8702C9.06 10.8602 8.94 10.8602 8.83 10.8702C7.6822 10.8313 6.59464 10.3471 5.79763 9.52019C5.00062 8.69332 4.55674 7.58869 4.56 6.44023C4.56 3.99023 6.54 2.00023 9 2.00023C10.1762 1.97902 11.3127 2.42593 12.1595 3.24265C13.0062 4.05937 13.4938 5.179 13.515 6.35523C13.5362 7.53147 13.0893 8.66796 12.2726 9.51468C11.4559 10.3614 10.3362 10.849 9.16 10.8702ZM4.16 14.5602C1.74 16.1802 1.74 18.8202 4.16 20.4302C6.91 22.2702 11.42 22.2702 14.17 20.4302C16.59 18.8102 16.59 16.1702 14.17 14.5602C11.43 12.7302 6.92 12.7302 4.16 14.5602V14.5602Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </template>
                    Používatelia
                </AdminNavItem>
            </ul>

            <div class="uppercase text-sm font-semibold text-sliver mt-8 mb-3 px-2">
                System
            </div>

            <ul>
                <AdminNavItem route-name="admin.failed-jobs.index">
                    Failed jobs
                </AdminNavItem>
            </ul>
        </div>
    </aside>
</template>