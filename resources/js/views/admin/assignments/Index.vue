<template>
    <div>
        <PageHeader title="Zadania">
            <AppButton 
                    :to="{name: 'admin.assignments.create'}"
                    size="small"
                >
                    Nové zadanie
                </AppButton>
        </PageHeader>

        <AdminTable>
            <TableHead :head="['Názov zadania', 'Status']"></TableHead>

            <TableRow v-for="item in assignments" :key="item.id">
                <TableCell>
                    <router-link :to="{name: 'admin.assignments.edit', params: {id: item.id}}" class="font-semibold text-primary">
                        {{ item.title }}
                    </router-link>
                    <span v-if="item.is_current" class="font-normal">
                        AKtualne
                    </span>
                </TableCell>

                <TableCell>
                    <span v-if="item.status === 'publish'" class="py-1 px-3 bg-green-300 rounded-lg">
                        Publikovane
                    </span>
                    <span v-else-if="item.status === 'draft'" class="py-1 px-3 bg-red-300 rounded-lg">
                        Koncept
                    </span>
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

onMounted(() => {
    getAssignments()
})
</script>

<style>

</style>