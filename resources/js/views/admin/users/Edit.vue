<template>
    <div>
        <PageHeader title="Upraviť používateľa">
            <AppButton @click="updateUser" size="small" button>Uložiť</AppButton>
        </PageHeader>

        <AdminCard>
            <div>
                <table>
                    <tr>
                        <td>Meno:</td>
                        <td>{{ user.name }}</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>{{ user.email }}</td>
                    </tr>
                </table>
            </div>
    
            <div>
                <label for="">Rola</label>
                <select v-model="user.role">
                    <option v-for="item in role" :key="item">
                        {{ item }}
                    </option>
        
                </select>
            </div>
        </AdminCard>
    </div>
</template>

<script setup>
import axios from 'axios';
import AdminCard from '../../../components/AdminCard.vue';
import { useRoute } from 'vue-router';
import { onMounted, ref } from 'vue';
import { role } from '@/enums/role';
import AppButton from '@/components/AppButton.vue';
import PageHeader from '@/components/admin/PageHeader.vue';

const route = useRoute()
const user = ref({})

async function getUser() {
    const res = await axios.get(`/api/users/${route.params.id}`)

    user.value = res.data
}

const updateUser = async () => {
    const res = await axios.put(`/api/users/${user.value.id}`, user.value)
    console.log(res)
}

onMounted(() => {
    getUser()
})
</script>
