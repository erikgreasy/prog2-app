<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import axios from 'axios'
import PageHeader from '@/components/admin/PageHeader.vue';
import AdminTable from '@/components/admin/AdminTable.vue';
import TableHead from '@/components/admin/TableHead.vue';
import TableRow from '../TableRow.vue';
import TableCell from '@/components/admin/TableCell.vue';
import AppInput from '@/components/admin/forms/AppInput.vue';
import debounce from 'debounce'
import { useAssignmentsIndex } from '@/composables/assignmentsIndex'

const users = ref([])

const search = ref('')

const { assignments, getAssignments } = useAssignmentsIndex()

const getStudents = async () => {
    const res = await axios.get(`/api/students?search=${search.value}`)
    users.value = res.data
}

onMounted(() => {
    getAssignments()
    getStudents()
})

watch(search, debounce(async (oldSearch, newSearch) => {
    console.log('change search: ' + search.value)
    getStudents()
}, 500))

const assignmentTableHeadCols = computed(() => {
    return assignments.value.map((assignment, index) => `Zadanie ${index + 1}`)
})
</script>

<template>
    <div>
        <PageHeader title="Študenti" />
        
        <div class="w-1/2 mb-3">
            <AppInput v-model="search" placeholder="Vyhľadávať študentov..." />
        </div>

        <AdminTable>
            <TableHead :head="['Meno'].concat(assignmentTableHeadCols)" />

            <TableRow v-for="item in users" :key="item.id">
                <TableCell>
                    <router-link :to="{name: 'admin.students.show', params: {id: item.id}}"  class="font-semibold text-primary">
                        {{ item.name }}
                    </router-link>
                </TableCell>
                
                <TableCell v-for="assignment in assignments.slice().reverse()">
                    {{ item.final_assignment_submissions.find(submission => submission.assignment_id == assignment.id)?.points || '-' }}

                </TableCell>
            </TableRow>
        </AdminTable>
    </div>
</template>
