<script setup>
import CompilationStats from "@/components/public/assignments/CompilationStats.vue";
import PrismCode from "@/components/PrismCode.vue";
import {ref} from "vue";
import SubmissionTestCase from "@/components/public/assignments/SubmissionTestCase.vue";
import SubmissionTestScenario from "@/components/public/assignments/SubmissionTestScenario.vue";

defineProps({
    submission: Object,
})

const accordionIndex = ref(null)

const togglAccordion = index => {
    if (accordionIndex.value === index) {
        accordionIndex.value = null

        return
    }

    accordionIndex.value = index
}
</script>

<template>
    <div>
        <SubmissionTestScenario
            v-for="(resultScenario, index) in submission?.result_scenarios"
            :key="resultScenario.id"
            :id="index"
            :result-scenario="resultScenario"
            :is-open="index === accordionIndex"
            @toggle="id => togglAccordion(id)"
        />
    </div>
</template>
