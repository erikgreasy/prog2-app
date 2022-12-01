<template>
    <AdminCard>
        Edit user {{ user.name }}
    </AdminCard>
</template>

<script setup>
import axios from 'axios';
import AdminCard from '../../../components/AdminCard.vue';
import { useRoute } from 'vue-router';
import { onMounted, ref } from 'vue';

const route = useRoute()
const user = ref({})

async function getUser() {
    const res = await axios.get(`/api/users/${route.params.id}`)

    user.value = res.data
}

onMounted(() => {
    getUser()
})
</script>
