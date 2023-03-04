<script setup>
import AdminTable from '@/components/admin/AdminTable.vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import TableCell from '@/components/admin/TableCell.vue';
import TableHead from '@/components/admin/TableHead.vue';
import axios from 'axios'
import { onMounted, ref } from 'vue'
import TableRow from '../TableRow.vue';

const users = ref([])

const getUsers = async () => {
    const res = await axios.get('/api/users')
    console.log(res)
    users.value = res.data
}

onMounted(() => {
    getUsers()
})
</script>

<template>
    <div>
        <PageHeader title="Používatelia" />
        
        <AdminTable>
            <TableHead :head="['Používateľ', 'Rola']"></TableHead>

            <TableRow v-for="item in users" :key="item.id">
                <TableCell>
                    <router-link :to="{name: 'admin.users.edit', params: {id: item.id}}" class="font-semibold text-primary">
                        {{ item.name }}
                    </router-link>
                </TableCell>
                <TableCell>
                    {{ item.role }}
                </TableCell>
            </TableRow>
        </AdminTable>
    </div>
</template>
