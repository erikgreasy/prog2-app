<script setup>
import AdminTable from "@/components/admin/AdminTable.vue";
import { onMounted, ref } from "vue";
import TableRow from "../TableRow.vue";
import TableHead from '@/components/admin/TableHead.vue'

import TableCell from "@/components/admin/TableCell.vue";
const failedJobs = ref([])

onMounted(async () => {
    const res = await axios.get('/api/failed-jobs')
    failedJobs.value = res.data
    console.log(res)
})
</script>

<template>
    <div>
        <AdminTable>
            <TableHead :head="['Dátum', 'Chyba',]" />

            <TableRow v-for="job in failedJobs" :key="job.id">
                <TableCell>
                    {{ job.failed_at }}
                </TableCell>

                <TableCell>
                    {{ job.exception.slice(0, 100) }}...
                </TableCell>
            </TableRow>
        </AdminTable>
    </div>
</template>