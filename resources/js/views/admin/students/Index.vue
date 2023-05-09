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
import StudentOverview from "@/components/admin/StudentOverview.vue";
import {useRoute} from "vue-router";

const route = useRoute()

const users = ref([])

const params = route.query

const search = ref(params.search || '')

const { assignments, getAssignments } = useAssignmentsIndex()

const loading = ref(false)

const page = ref(1)

const changePage = url => {
    const urlObj = new URL(url)

    const searchParams = urlObj.searchParams
    const newPage = searchParams.get('page')

    page.value = newPage
    getStudents()
}

const getStudents = async () => {
    loading.value = true
    const res = await axios.get(`/api/students?search=${search.value}&page=${page.value}`)
    users.value = res.data

    loading.value = false
}

onMounted(() => {
    getAssignments()
    getStudents()
})

watch(search, debounce(async (oldSearch, newSearch) => {
    console.log('change search: ' + search.value)
    page.value = 1
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

        <div class="py-5">
            <div v-if="loading">
                Loading...
            </div>

            <div v-else-if="users.data?.length">
                <StudentOverview
                    v-for="student in users.data"
                    :key="student.id"
                    :assignments="assignments"
                    :student="student"
                />
            </div>

            <div v-else>
                Neboli nájdené žiadne výsledky
            </div>
        </div>

        <div v-if="users.data" class="flex gap-x-4 items-center justify-center">
            <component
                v-for="(link, index) in users.meta.links"
                :is="link.url ? 'button' : 'span'"
                :key="index"
                @click="changePage(link.url)"
                v-html="link.label"
                :class="{
                    'font-bold' : link.active,
                    'opacity-50': !link.url,
                }"
            ></component>
        </div>
<!--        {{users}}-->
    </div>
</template>
