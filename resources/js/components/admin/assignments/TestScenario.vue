<script setup>
import { ref } from "vue";
import AdminCard from '@/components/AdminCard.vue'
import AppTextarea from '@/components/admin/forms/AppTextarea.vue'
import { useRoute } from "vue-router";
import NewCase from "./NewCase.vue";

const route = useRoute()

const emit = defineEmits([
    'deleted'
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
</script>

<template>
    <AdminCard class="mb-5">
        <div class="mb-5">
            <h3 class="font-semibold">
                <RouterLink :to="{name: 'admin.assignment-tests.show', params: {assignment_id: route.params.id, test_id: scenario.id}}">
                    {{ scenario.title }} ({{ scenario.cases.length }} casov)
                </RouterLink>
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

        <button @click="deleteScenario">Delete</button>
    </AdminCard>
</template>
