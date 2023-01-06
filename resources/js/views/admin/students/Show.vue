<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const student = ref(null)

onMounted(async () => {
    try {
        const res = await axios.get(`/api/students/${route.params.id}`)
        student.value = res.data
    } catch(err) {
        alert('Nepodarilo sa nacitat studenta')
        console.log(err.response)
    }
})
</script>

<template>
    <div>
        {{ student }}
    </div>
</template>
