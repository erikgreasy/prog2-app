<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import PageHeader from '@/components/admin/PageHeader.vue';

const users = ref([])

const getStudents = async () => {
    const res = await axios.get('/api/students')
    users.value = res.data
}

onMounted(() => {
    getStudents()
})
</script>

<template>
    <div>
        <PageHeader title="Študenti" />
        
        <AdminCard>
            <div class="overflow-x-auto relative shadow">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6">
                                Názov zadania
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in users" :key="item.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <router-link :to="{name: 'admin.students.show', params: {id: item.id}}">
                                    {{ item.name }}
                                </router-link>
                            </th>
                            <td>
                                <RouterLink :to="{name: 'admin.users.submissions', params: {userId: item.id}}">
                                    Prehlad
                                </RouterLink>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </AdminCard>
    </div>
</template>
