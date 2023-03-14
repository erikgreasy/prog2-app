<script setup>
import AssignmentHeader from "@/components/public/assignments/AssignmentHeader.vue";
import { useAssignments } from "@/composables/assignments";
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import PrismCode from '@/components/PrismCode.vue'

const route = useRoute()

const accordionIndex = ref(null)

const assignment = ref({})
const currentSection = ref('content')
  
const getAssignment = async () => {
    const res = await axios.get(`/api/assignments/slug/${route.params.slug}`)
    assignment.value = res.data
}

const submission = ref(null)

onMounted(async () => {
    await getAssignment()
    
    const res = await axios.get(`/api/assignments/${assignment.value.id}/submissions/${route.params.index}`)
    submission.value = res.data
    console.log(res)
})

const togglAccordion = index => {
    if (accordionIndex.value === index) {
        accordionIndex.value = null

        return
    }

    accordionIndex.value = index
}
</script>

<template>
    <div class="container">
        <AssignmentHeader :assignment="assignment" />

        <h2 class="text-2xl font-bold text-center">Report: 1. pokus</h2>

        <div class="flex justify-center gap-x-5 text-sm mt-10 mb-20">
            <div class="rounded-lg bg-green-200 py-1.5 px-5">
                Build status: 
            </div>
            <div class="rounded-lg bg-green-200 py-1.5 px-5">
                GCC warnings: 1
            </div>
            <div class="rounded-lg bg-green-200 py-1.5 px-5">
                GCC errors: 0
            </div>
        </div>

        <div>
            <div v-for="(resultScenario, index) in submission?.result_scenarios" 
                :key="resultScenario.id" 
                class="mb-10"
            >
                <div
                    @click="togglAccordion(index)"
                    class="border-b-2 cursor-pointer py-4"
                    :class="{'border-red-400' : !resultScenario.is_success, 'border-green-400' : resultScenario.is_success}"
                >
                    <span class="text-xl font-bold">
                        {{ ++index }}. Testovací scenár
                    </span>
                    {{ resultScenario.scenario.title }} 
                    {{ resultScenario.is_success ? 'OK' : 'FAIL' }}
                    {{ resultScenario.points }} / {{ resultScenario.scenario.points }}
                </div>
                <div v-show="index === accordionIndex" v-for="resultCase in resultScenario.result_cases" :key="resultCase.id" class="mb-4">
                    <div>
                        Pripad: 
                        <span v-if="resultCase.is_success" class="bg-green-400 py-0.5 px-1 rounded-sm font-semibold text-white">OK</span>
                        <span v-else class="bg-red-400 py-0.5 px-1 rounded-sm font-semibold text-white">FAIL</span>
                    </div>
                    <div v-if="resultCase.cmd_in">
                        CMD IN: <PrismCode>{{ resultCase.cmd_in }}</PrismCode>
                    </div>
                    <div>
                        STD IN: {{ resultCase.std_in }}
                    </div>
                    <div>
                        STD OUT: {{ resultCase.std_out }}
                    </div>
                    <div>
                        spravny STD OUT
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>