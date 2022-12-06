<template>
    <div>
        <AdminCard>
            <AssignmentForm :assignment="assignment" @store-assignment="storeAssignment" />
        </AdminCard>
        
    </div>
</template>

<script setup>
import axios from 'axios'
import AssignmentForm from '../../../components/AssignmentForm.vue'
import AdminCard from '../../../components/AdminCard.vue'
import { useRouter } from 'vue-router'
import { ref, toRaw, watch } from 'vue';
import useEventsBus from '@/eventBus.js';

const { emit, bus } = useEventsBus()
const router = useRouter()

const assignment = ref({
    title: '',
    deadline: '',
    content: {}
})

const storeAssignment = async () => {
    console.log('try to store following assignment')

    // try {
    //     const res = await axios.post('/api/assignments', assignment.value)
    //     const newAssignment = res.data

    //     router.push({name: 'admin.assignments.edit', params: {id: newAssignment.id}})
    // } catch(err) {
    //     console.log(err)
    //     console.log(err.response)
    // }
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

</script>

<style>

</style>