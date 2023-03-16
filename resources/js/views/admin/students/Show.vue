<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import PageHeader from '@/components/admin/PageHeader.vue';

const route = useRoute()
const student = ref({})
const assignments = ref([])

const getStudent = async id => {
    try {
        const res = await axios.get(`/api/students/${id}`)
        student.value = res.data
    } catch(err) {
        alert('Nepodarilo sa nacitat studenta')
        console.log(err.response)
    }
}

const getAssignments = async () => {
    try {
        const res = await axios.get(`/api/assignments`)
        assignments.value = res.data
    } catch(err) {
        alert('Nepodarilo sa nacitat zadania')
        console.log(err.response)
    }
}

onMounted(async () => {
    getStudent(route.params.id)
    getAssignments()
})
</script>

<template>
    <div>
        <PageHeader :title="`Detail študenta ${student?.name}`" />
        
        <div>
            Email: {{ student.email }}
        </div>

        <hr class="my-10">

        <div v-for="assignment in assignments" :key="assignment.id" class="mb-10">
            <h3 class="text-primary font-bold mb-3">{{ assignment.title }}</h3>

            <div v-if="student.submissions?.filter(item => item.id === assignment.id).length" class="pl-5">
                <div v-for="submission in student.submissions.filter(item => item.id === assignment.id)" :key="submission.id">
                    <RouterLink 
                        :to="{name: 'admin.users.submissions.show', params: {userId: student.id, id: submission.id}}"
                        class="text-primary"
                    >
                        Odovzdanie {{ submission.created_at }}
                    </RouterLink>
                </div>
            </div>
            <div v-else>
                Zatiaľ žiadne odovzdanie
            </div>
        </div>

        <div>
            Celkovy pocet bodov: 
        </div>
        <!-- {{ student }} -->
    </div>
</template>
