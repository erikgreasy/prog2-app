<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const submission = ref(null)

const route = useRoute()

onMounted(async () => {
    try {
        const res = await axios.get(`/api/users/${route.params.userId}/submissions/${route.params.id}`)
        submission.value = res.data
    } catch(err) {
        alert('Chyba!')
        console.error(err)
    }
})

</script>

<template>
    <div>
        here is detail of submission
        <div>
            {{ submission }}
        </div>
    </div>
</template>
