<template>
    <div class="grid grid-cols-12 gap-8">
        <AdminCard class="col-span-9">
            <AssignmentForm @store-assignment="updateAssignment" />

            <button class="text-red-600 mt-10" @click="deleteAssignment">Delete</button>
        </AdminCard>

        <AdminCard class="col-span-3">
            Bocny panel
        </AdminCard>
    </div>
</template>

<script setup>
import axios from 'axios'
import { onMounted, provide, ref, toRaw } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import AssignmentForm from '../../../components/AssignmentForm.vue'

const route = useRoute()
const router = useRouter()

const assignment = ref({})

provide('assignment', assignment)

const getAssignment = async () => {
    const res = await axios.get(`/api/assignments/${route.params.id}`)
    assignment.value = res.data
}

const deleteAssignment = async () => {
    const res = await axios.delete(`/api/assignments/${route.params.id}`)

    router.push({name: 'admin.assignments.index'})
}

const updateAssignment = async () => {
    console.log('try to update assignment')
    
    try {
        const res = await axios.put(`/api/assignments/${route.params.id}`, assignment.value)
        console.log(res)
        
        if(res.status === 200) {
            alert('success')
        }
    } catch(err) {
        alert('Pri ukladaní nasatala chyba')
        console.error(err)
    }
}

onMounted(() => {
    getAssignment()
})
</script>

<style>

</style>