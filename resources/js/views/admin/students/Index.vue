<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import PageHeader from '@/components/admin/PageHeader.vue';
import AdminTable from '@/components/admin/AdminTable.vue';
import TableHead from '@/components/admin/TableHead.vue';
import TableRow from '../TableRow.vue';
import TableCell from '@/components/admin/TableCell.vue';

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
        
        <AdminTable>
            <TableHead :head="['Meno']" />

            <TableRow v-for="item in users" :key="item.id">
                <TableCell>
                    <router-link :to="{name: 'admin.students.show', params: {id: item.id}}"  class="font-semibold text-primary">
                        {{ item.name }}
                    </router-link>
                </TableCell>
            </TableRow>
        </AdminTable>
    </div>
</template>
