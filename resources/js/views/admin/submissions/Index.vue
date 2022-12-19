<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const assignments = ref([])
const route = useRoute()


onMounted(async () => {
    const res = await axios.get(`/api/users/${route.params.userId}/submissions`)
    console.log(res)
    assignments.value = res.data
})

</script>

<template>
    <div>
        <div v-for="assignment in assignments" :key="assignment.id">
            <h3>{{ assignment.title }}</h3>
            
            <ul>
                <li v-for="submission in assignment.submissions">
                    {{ submission.points }}
                </li>
            </ul>
        </div>
    </div>
</template>
