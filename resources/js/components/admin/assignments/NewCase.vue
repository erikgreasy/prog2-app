<script setup>
import AdminCard from '@/components/AdminCard.vue';
import AppButton from '@/components/AppButton.vue';
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import AppInput from '../forms/AppInput.vue';
import InputWithError from '../forms/InputWithError.vue';

const route = useRoute()

const emit = defineEmits([
    'created'
])

const errors = ref({})

const testCase = ref({})

const creating = ref(false)

const storeCase = async () => {
    try {
        const res = await axios.post(`/api/assignments/${route.params.assignment_id}/tests/${route.params.test_id}/cases`, testCase.value)
        console.log(res)
        testCase.value = {}
        creating.value = false
        emit('created')
    } catch (err) {
        console.log(err)
        const res = err.response
        errors.value = res.data.errors
    }
}
</script>

<template>
    <div>
        <AppButton v-if="!creating" size="small" @click="creating = true">
            Nový case
        </AppButton>
                 
        <AdminCard v-if="creating">
            <form @submit.prevent="storeCase">
                <InputWithError label="cmd in" :errors="errors.cmd_in">
                    <AppInput v-model="testCase.cmd_in" />
                </InputWithError>
    
                <InputWithError label="std in" :errors="errors.std_in">
                    <AppInput v-model="testCase.std_in" />
                </InputWithError>
    
                <InputWithError label="std out" :errors="errors.std_out">
                    <AppInput v-model="testCase.std_out" />
                </InputWithError>
    
                <InputWithError label="err out" :errors="errors.err_out">
                    <AppInput v-model="testCase.err_out" />
                </InputWithError>
    
                <AppButton button>Save</AppButton>
            </form>
        </AdminCard>
    </div>
</template>
