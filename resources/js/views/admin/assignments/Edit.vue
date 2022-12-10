<template>
    <div>
        <div class="flex justify-between items-center mb-5">
            <h1 class="font-semibold text-2xl">Upraviť zadanie</h1>

            <div class="flex items-center gap-x-5">
                <AppButton 
                    v-if="assignment.slug" 
                    :to="{name: 'assignments.show', params: {slug: assignment.slug}}"
                    size="small"
                    type="outline"
                >Zobraziť</AppButton>

                <AppButton @click="submitForm" size="small" button>Uložiť</AppButton>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-8">
            <div class="col-span-9">
                <AssignmentForm @submit-form="alert('daco')" :errors="errors" />
            </div>
    
            <AdminCard class="col-span-3">
                Bocny panel
            </AdminCard>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios'
import { onMounted, provide, ref, toRaw, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import AppButton from '../../../components/AppButton.vue';
import AssignmentForm from '../../../components/AssignmentForm.vue'
import useEventsBus from '@/eventBus.js'

const route = useRoute()
const router = useRouter()

const assignment = ref({})
const errors = ref([])

const { emit, bus } = useEventsBus()

const componentEmits = defineEmits(['storeAssignment'])

const submitForm = () => {
    errors.value = {}

    // emit event to get editor data
    emit('storingAssignment')



    // enjoy!
}

watch(() => bus.value.get('contentEditor'), async contentPromise => {
    const outputData = await contentPromise[0]

    assignment.value.content = outputData

    // after receiving, emit event to send request
    updateAssignment()
})



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
        const res = err.response

        if(res.status === 422) {
            console.log(res)
            errors.value = res.data.errors
            return
        }

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