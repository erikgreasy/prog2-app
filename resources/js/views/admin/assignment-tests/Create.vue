<script setup>
import AppButton from '@/components/AppButton.vue'
import PageHeader from '@/components/admin/PageHeader.vue'
import AppInput from '@/components/admin/forms/AppInput.vue';
import InputGroup from '@/components/admin/forms/InputGroup.vue';
import { useRoute, useRouter } from 'vue-router';
import { ref } from 'vue'

const route = useRoute()
const router = useRouter()

const errors = ref({})

const submitTest = async () => {
    try {
        const res = await axios.post(`/api/assignments/${route.params.id}/tests`, test.value)

        console.log(res)
        router.push({name: 'admin.assignment-tests.index', params: {id: route.params.id}})
    } catch (err) {
        console.log(err)
        const res = err.response

        console.log(res)
        errors.value = res.data.errors
    }
}

const test = ref({})
</script>

<template>
    <form @submit.prevent="submitTest">
        <PageHeader :title="`Nový test`">
            <AppButton size="small" button>Uložiť</AppButton>
        </PageHeader>

        <InputGroup>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Názov:</label>
            <AppInput v-model="test.title" :errors="errors.title" />
        </InputGroup>

        <InputGroup>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Max. počet bodov:</label>
            <AppInput v-model="test.points" type="number" :errors="errors.points" />
        </InputGroup>
    </form>
</template>