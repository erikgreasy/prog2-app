<script setup>
import axios from 'axios';
import AdminCard from '../../../components/AdminCard.vue';
import { useRoute } from 'vue-router';
import { onMounted, ref } from 'vue';
import { role } from '@/enums/role';
import AppButton from '@/components/AppButton.vue';
import PageHeader from '@/components/admin/PageHeader.vue';
import AppSelect from '@/components/admin/forms/AppSelect.vue';
import InputLabel from '@/components/admin/forms/InputLabel.vue';
import { useNotificationsStore } from "@/stores/notifications";
import InputGroup from '@/components/admin/forms/InputGroup.vue';
import AppInput from '@/components/admin/forms/AppInput.vue';

const route = useRoute()
const user = ref({})

const notificationsStore = useNotificationsStore()

async function getUser() {
    const res = await axios.get(`/api/users/${route.params.id}`)

    user.value = res.data
}

const updateUser = async () => {
    try {
        const res = await axios.put(`/api/users/${user.value.id}`, user.value)
        notificationsStore.addNotification('Úspešne aktualizované')
        console.log(res)
    } catch (err) {
        console.log(err)
        alert('Pri aktualizovani nastala chyba')
    }
}

onMounted(() => {
    getUser()
})
</script>

<template>
    <div>
        <PageHeader title="Upraviť používateľa">
            <AppButton @click="updateUser" size="small" button>Aktualizovať</AppButton>
        </PageHeader>

        <AdminCard>
            <InputGroup>
                <InputLabel>Meno:</InputLabel>
                <AppInput :value="user.name" disabled />
            </InputGroup>
            
            <InputGroup>
                <InputLabel>Email:</InputLabel>
                <AppInput :value="user.email" disabled />
            </InputGroup>

            <div>
                <InputLabel>Rola</InputLabel>
                <AppSelect v-model="user.role">
                    <option v-for="item in role" :key="item">
                        {{ item }}
                    </option>
                </AppSelect>
            </div>
        </AdminCard>
    </div>
</template>
