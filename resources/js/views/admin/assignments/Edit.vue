<template>
    <div>
        <PageHeader title="Upraviť zadanie">
            <AppButton 
                v-if="realSlug" 
                :to="{name: 'assignments.show', params: {slug: realSlug}}"
                size="small"
                type="outline"
            >Zobraziť</AppButton>

            <AppButton @click="submitForm" size="small" button>Uložiť</AppButton>
        </PageHeader>

        <div class="grid grid-cols-12 gap-8">
            <div class="col-span-9">
                <AssignmentForm @submit-form="alert('daco')" :errors="errors" />
            </div>
    
            <AdminCard class="col-span-3">
                <select v-model="assignment.status">
                    <option value="publish">Publikované</option>
                    <option value="draft">Koncept</option>
                </select>
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
import PageHeader from '@/components/admin/PageHeader.vue';

const route = useRoute()
const router = useRouter()

const assignment = ref({})
const errors = ref([])
const realSlug = ref(null)

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
    realSlug.value = res.data.slug
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
            realSlug.value = assignment.value.slug
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