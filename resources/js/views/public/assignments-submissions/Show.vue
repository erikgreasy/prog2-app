<script setup>
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import PrismCode from '@/components/PrismCode.vue'
import CompilationStats from "@/components/public/assignments/CompilationStats.vue";
import SubmissionPointsTable from "@/components/public/assignments/SubmissionPointsTable.vue";
import SubmissionTestScenarios from "@/components/public/assignments/SubmissionTestScenarios.vue";
import SubmissionSubmittedFile from "@/components/public/assignments/SubmissionSubmittedFile.vue";

const route = useRoute()

const assignment = ref({})
const currentSection = ref('content')

const getAssignment = async () => {
    const res = await axios.get(`/api/assignments/slug/${route.params.slug}`)
    assignment.value = res.data
}

const submission = ref(null)

onMounted(async () => {
    await getAssignment()

    const res = await axios.get(`/api/assignments/${assignment.value.id}/submissions/${route.params.submission_id}`)
    submission.value = res.data
    console.log(res)
})
</script>

<template>
    <div class="container" v-if="submission">
        <h2 class="text-2xl font-bold text-center mb-10">Report: {{ submission.try }}. pokus</h2>

        <div v-if="submission.fail_messages" class="bg-red-600 py-5 px-10 text-center text-white mt-10">
            <h3>Zadanie nebolo ohodnotené kvôli nasledovnej chybe:</h3>
            <div class="text-xl font-bold mt-3">
                {{ submission.fail_messages.public_output }}
            </div>
        </div>

        <div v-else>
            <div class="border border-primaryDark rounded-lg px-10">
                <SubmissionPointsTable :submission="submission" />
            </div>

            <hr class="my-20">

            <SubmissionTestScenarios :submission="submission" />

            <hr class="my-20">

            <SubmissionSubmittedFile :submission="submission" />
        </div>
    </div>
</template>
