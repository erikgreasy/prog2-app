<script setup>
import AssignmentHeader from "@/components/public/assignments/AssignmentHeader.vue";
import { useAssignments } from "@/composables/assignments";
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";

const route = useRoute()

const assignment = ref({})
const currentSection = ref('content')
  
const getAssignment = async () => {
    const res = await axios.get(`/api/assignments/slug/${route.params.slug}`);
    assignment.value = res.data.data;
}

const submission = ref(null)

onMounted(async () => {
    await getAssignment()
    
    const res = await axios.get(`/api/assignments/${assignment.value.id}/submissions/${route.params.index}`)
    submission.value = res.data
    console.log(res)
})
</script>

<template>
    <div class="container">
        <AssignmentHeader :assignment="assignment" />

        <h2 class="text-2xl font-bold text-center">Report: 1. pokus</h2>

        <div class="flex justify-center gap-x-5 text-sm">
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
            <div v-for="scenario in submission?.report?.scenarios" :key="scenario.id" class="mb-10">
                <h2 class="text-xl">Scenar: {{ scenario.title }}</h2>
                <div v-for="testCase in scenario.cases" :key="testCase.id" class="mb-4">
                    <div>
                        Pripad: {{ testCase.success ? 'OK' : 'FAIL' }}
                    </div>
                    <div>
                        STD IN: 
                    </div>
                    <div>
                        STD OUT: {{ testCase.std_out }}
                    </div>
                    <div>
                        spravny STD OUT
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>