<script setup>
import AdminNavItem from '../../components/AdminNavItem.vue';
</script>

<template>
    <aside class="fixed z-10 top-12 left-0 w-64 h-[calc(100%-2.5rem)]" aria-label="Sidenav">
        <div
            class="overflow-y-auto py-5 px-3 h-full bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <ul class="space-y-2">
                <AdminNavItem route-name="admin.dashboard">
                    Dashboard
                </AdminNavItem>

                <AdminNavItem route-name="admin.assignments.index">
                    Zadania
                </AdminNavItem>
                
                <AdminNavItem route-name="admin.users.index">
                    Používatelia
                </AdminNavItem>
            </ul>
        </div>
    </aside>
</template>