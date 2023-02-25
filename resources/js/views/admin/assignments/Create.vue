<script setup>
import axios from 'axios'
import AssignmentForm from '../../../components/AssignmentForm.vue'
import AdminCard from '../../../components/AdminCard.vue'
import AppButton from '@/components/AppButton.vue'
import { useRouter } from 'vue-router'
import { ref, toRaw, watch, provide } from 'vue';
import useEventsBus from '@/eventBus.js';
import PageHeader from '@/components/admin/PageHeader.vue'

const { emit, bus } = useEventsBus()
const router = useRouter()

const assignment = ref({
    title: '',
    deadline: '',
    content: {}
})

const errors = ref([])

const storeAsDraft = () => {
    assignment.value.status = 'draft'
    store()
}

const store = async () => {
    // console.log('try to store following assignment')

    try {
        const res = await axios.post('/api/assignments', assignment.value)
        const newAssignment = res.data

        router.push({name: 'admin.assignments.edit', params: {id: newAssignment.id}})
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

watch(() => bus.value.get('contentEditor'), async contentPromise => {
    // console.log('response from content editor!')

    const outputData = await contentPromise[0]

    assignment.value.content = outputData
    storeAssignment()
    // console.log('Promise resolved')
    // console.log(outputData)
    
    // console.log(contentPromise[0])
    // contentPromise.value.then(data => {
    // })

})

provide('assignment', assignment)

</script>

<template>
    <div>
        <PageHeader title="Upraviť zadanie">
            <AppButton @click="storeAsDraft" size="small" type="outline" button>Uložiť ako koncept</AppButton>
            <AppButton @click="store" size="small" button>Publikovať</AppButton>
        </PageHeader>

        <AdminCard>
            <AssignmentForm :errors="errors" @store-assignment="storeAssignment" />
        </AdminCard>
    </div>
</template>
