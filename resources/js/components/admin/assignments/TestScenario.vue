<script setup>
import { ref } from "vue";
import AdminCard from '@/components/AdminCard.vue'
import { useRoute } from "vue-router";
import NewCase from "./NewCase.vue";
import AppButton from "@/components/AppButton.vue";
import InputWithError from "@/components/admin/forms/InputWithError.vue";
import AppInput from "@/components/admin/forms/AppInput.vue";
import TestScenarioCases from '@/components/admin/assignments/TestScenarioCases.vue'

const route = useRoute()

const emit = defineEmits([
    'deleted',
    'updated',
])

const props = defineProps({
    scenario: Object,
})

const deleteScenario = async () => {
    if (!confirm('Naozaj chcete odstranit?')) return

    try {
        const res = await axios.delete(`/api/assignments/${route.params.id}/tests/${props.scenario.id}`)
        console.log(res)
        emit('deleted')
    } catch (err) {
        console.log(err)
        alert('nastala chyba')
    }
}

const casesVisible = ref(false)
const editing = ref(false)

const errors = ref({})

const updateScenario = async () => {
    try {
        const res = await axios.put(`/api/assignments/${route.params.id}/tests/${props.scenario.id}`, props.scenario)
        emit('updated')
        editing.value = false
        console.log(res)
    } catch (err) {
        const res = err.response

        if (res.status === 422) {
            errors.value = res.data.errors

            return
        }

        alert('Nastala chyba')
    }
}
</script>

<template>
    <AdminCard class="mb-5 relative">
        <div class="mb-5">
            <h3 class="font-semibold">
                {{ scenario.title }} ({{ scenario.cases.length }} casov)
            </h3>
            <p>{{ scenario.points }} body</p>
        </div>

        <!-- <div v-show="!isHidden">
            <template v-if="scenario.cases.length">
                <div v-for="testCase in scenario.cases" :key="testCase.id" class="grid grid-cols-2 gap-x-10">
                    <div>
                        <label for="">Vstup</label>
                        <AppTextarea v-model="testCase.input" disabled/>
                    </div>
                    <div>
                        <label for="">Očakávaný výstup</label>
                        <AppTextarea v-model="testCase.expected_output" disabled/>
                    </div>
                </div>
            </template>

            <div v-else>
                Zatiaľ žiadne test cases
            </div>

            <div>
                <NewCase />
            </div>
        </div> -->

        <div class="absolute top-5 right-5 flex gap-x-3">
            <AppButton :to="{name: 'admin.assignment-tests.edit', params: {assignment_id: scenario.assignment_id, test_id: scenario.id}}" type="primary" size="xs">
                Upraviť scénár
            </AppButton>
    
            <AppButton v-if="!editing" @click="deleteScenario" type="danger" size="xs" button>
                Odstrániť scénár
            </AppButton>
        </div>
    </AdminCard>
</template>
