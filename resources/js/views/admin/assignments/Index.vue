<template>
    <div>
        <PageHeader title="Zadania">
            <AppButton :to="{ name: 'admin.assignments.create' }" size="small">
                Nové zadanie
            </AppButton>
        </PageHeader>

        <AdminTable>
            <TableHead :head="['Názov zadania', 'Status']"></TableHead>

            <TableRow v-for="item in assignments" :key="item.id">
                <TableCell>
                    <router-link :to="{ name: 'admin.assignments.edit', params: { id: item.id } }"
                        class="font-semibold text-primary">
                        {{ item.title }}
                    </router-link>
                    <span v-if="item.is_current" class="py-1 px-3 bg-green-300 rounded-lg inline-block ml-3">
                        Aktuálne
                    </span>
                </TableCell>

                <TableCell>
                    <span v-if="item.is_published" class="py-1 px-3 bg-green-300 rounded-lg">
                        Publikované
                    </span>
                    <span v-else class="py-1 px-3 bg-red-300 rounded-lg">
                        Koncept
                    </span>
                </TableCell>
                <TableCell class="w-[1%]">
                    <div class="flex items-center gap-x-5">
                        <router-link :to="{ name: 'admin.assignments.edit', params: { id: item.id } }">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                        </router-link>
                        <button @click="deleteAssignment(item.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </button>
                    </div>
                </TableCell>
            </TableRow>
        </AdminTable>

        <!-- <div class="overflow-x-auto relative shadow">
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
                        <th scope="row" class="py-4 px-6 whitespace-nowrap dark:text-white">
                            <router-link :to="{name: 'admin.assignments.edit', params: {id: item.id}}" class="font-semibold text-primary">
                                {{ item.title }}
                            </router-link>
                            <span v-if="item.is_current" class="font-normal">
                                AKtualne
                            </span>
                        </th>
                        <td class="py-4 px-6">
                            <span v-if="item.status === 'publish'" class="py-1 px-3 bg-green-300 rounded-lg">
                                Publikovane
                            </span>
                            <span v-else-if="item.status === 'draft'" class="py-1 px-3 bg-red-300 rounded-lg">
                                Koncept
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div> -->
    </div>
</template>

<script setup>
import AdminTable from '@/components/admin/AdminTable.vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import axios from 'axios'
import { onMounted, ref } from 'vue';
import AppButton from '../../../components/AppButton.vue';
import TableCell from '@/components/admin/TableCell.vue'
import TableHead from '@/components/admin/TableHead.vue'
import TableRow from '../TableRow.vue';

const assignments = ref([])

const getAssignments = async () => {
    const res = await axios.get('/api/assignments')
    assignments.value = res.data
    console.log(res)
}

const deleteAssignment = async id => {
    if (!confirm('Naozaj chcete odstrániť zadanie?')) {
        return
    }

    const res = await axios.delete(`/api/assignments/${id}`)
    getAssignments()
}

onMounted(() => {
    getAssignments()
})
</script>

<style></style>
