<template>
    <div>
        <div class="flex justify-between items-center mb-5">
            <h1 class="font-semibold text-2xl">Zadania</h1>

            <div>
                <AppButton 
                    :to="{name: 'admin.assignments.create'}"
                    size="small"
                >
                    Nové zadanie
                </AppButton>
            </div>
        </div>

        <div class="overflow-x-auto relative shadow">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="py-3 px-6 font-semibold">
                            Názov zadania
                        </th>
                        <th scope="col" class="py-3 px-6 font-semibold">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in assignments" :key="item.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="py-4 px-6 font-semibold text-primary whitespace-nowrap dark:text-white">
                            <router-link :to="{name: 'admin.assignments.edit', params: {id: item.id}}">
                                {{ item.title }}
                            </router-link>
                        </th>
                        <td class="py-4 px-6">
                            Aktívne
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios'
import { onMounted, ref } from 'vue';
import AppButton from '../../../components/AppButton.vue';

const assignments = ref([])

const getAssignments = async () => {
    const res = await axios.get('/api/assignments')
    assignments.value = res.data
    console.log(res)
}

onMounted(() => {
    getAssignments()
})
</script>

<style>

</style>