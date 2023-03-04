<script setup>
import AppButton from '@/components/AppButton.vue'
import PageHeader from '@/components/admin/PageHeader.vue'
import AppInput from '@/components/admin/forms/AppInput.vue';
import AppTextarea from '@/components/admin/forms/AppTextarea.vue';
import InputGroup from '@/components/admin/forms/InputGroup.vue';
import { useRoute, useRouter } from 'vue-router';
import { onMounted, ref } from 'vue'
import InputLabel from '@/components/admin/forms/InputLabel.vue';

const route = useRoute()
const router = useRouter()

const errors = ref({})

const updateTest = async () => {
    try {
        const res = await axios.put(`/api/assignments/${route.params.assignment_id}/tests/${test.value.id}`, test.value)
        console.log(res)
        router.push({name: 'admin.assignment-tests.index', params: {id: route.params.assignment_id}})
    } catch (err) {
        console.log(err)
        const res = err.response

        console.log(res)
        errors.value = res.data.errors
    }
}

const addCase = () => {
    test.value.cases.push({})
}

const test = ref({})

const removeCase = index => {
    test.value.cases.splice(index, 1)
}

onMounted(async () => {
    try {
        const res = await axios.get(`/api/assignments/${route.params.assignment_id}/tests/${route.params.test_id}`)
        test.value = res.data
    } catch (err) {
        console.log(err)
    }
})
</script>

<template>
    <form @submit.prevent="updateTest">
        <PageHeader :title="`Edit test`">
            <AppButton size="small" button>Uložiť</AppButton>
        </PageHeader>

        <InputGroup>
            <InputLabel class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Názov:</InputLabel>
            <AppInput v-model="test.title" :errors="errors?.title" />
        </InputGroup>

        <InputGroup>
            <InputLabel class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Max. počet bodov:</InputLabel>
            <AppInput v-model="test.points" type="number" :errors="errors?.points" />
        </InputGroup>
    </form>
</template>
