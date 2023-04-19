<script setup>
import AppButton from '@/components/AppButton.vue'
import PageHeader from '@/components/admin/PageHeader.vue'
import AppInput from '@/components/admin/forms/AppInput.vue';
import AppTextarea from '@/components/admin/forms/AppTextarea.vue';
import InputGroup from '@/components/admin/forms/InputGroup.vue';
import { useRoute, useRouter } from 'vue-router';
import { onMounted, ref } from 'vue'
import InputLabel from '@/components/admin/forms/InputLabel.vue';
import AdminCard from '@/components/AdminCard.vue';
import InputWithError from '@/components/admin/forms/InputWithError.vue';

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
    <form v-if="test.id" @submit.prevent="updateTest">
        <PageHeader :title="`Upraviť testovací scenár`">
            <AppButton :to="{name: 'admin.assignment-tests.index', params: {id: test.assignment_id}}" type="outline" size="small">Späť</AppButton>
            <AppButton size="small" button>Uložiť</AppButton>
        </PageHeader>

        <div class="grid gap-y-5">
            <AdminCard>
                <div class="grid grid-cols-2 gap-x-10">
                    <InputGroup>
                        <InputLabel class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Názov:</InputLabel>
                        <AppInput v-model="test.title" :errors="errors?.title" />
                    </InputGroup>

                    <InputGroup>
                        <InputWithError label="Počet bodov:" :errors="errors.points">
                            <AppInput v-model="test.points" type="number" :errors="errors?.points" />
                        </InputWithError>
                    </InputGroup>
                </div>
            </AdminCard>

            <h3 class="text-center text-xl mb-5 mt-10">Testovacie prípady</h3>

            <AdminCard v-for="(testCase, index) in test.cases" :key="testCase.id" class="relative">
                <h4 class="mb-5">Case {{ index + 1 }}</h4>

                <AppButton @click="test.cases.splice(index, 1)" size="xs" type="danger" class="absolute top-5 right-5">
                    Odstrániť prípad
                </AppButton>

                <div class="grid grid-cols-5 gap-x-3">
                    <InputWithError label="Gcc macro defs:" :errors="errors[`cases.${index}.gcc_macro_defs`]">
                        <AppTextarea v-model="testCase.gcc_macro_defs" :errors="errors[`cases.${index}.gcc_macro_defs`]" />
                    </InputWithError>

                    <InputWithError label="cmd in:" :errors="errors[`cases.${index}.cmd_in`]">
                        <AppTextarea v-model="testCase.cmd_in" :errors="errors[`cases.${index}.cmd_in`]" />
                    </InputWithError>

                    <InputWithError label="Std in:" :errors="errors[`cases.${index}.std_in`]">
                        <AppTextarea v-model="testCase.std_in" :errors="errors[`cases.${index}.std_in`]" />
                    </InputWithError>

                    <InputWithError label="std out:" :errors="errors[`cases.${index}.std_out`]">
                        <AppTextarea v-model="testCase.std_out" :errors="errors[`cases.${index}.std_out`]" />
                    </InputWithError>

                    <InputWithError label="err out:" :errors="errors[`cases.${index}.err_out`]">
                        <AppTextarea v-model="testCase.err_out" :errors="errors[`cases.${index}.err_out`]" />
                    </InputWithError>
                </div>
            </AdminCard>

            <div class="text-center">
                <AppButton @click="test.cases.push({})" size="small">
                    Pridať prípad
                </AppButton>
            </div>
        </div>
    </form>
</template>
