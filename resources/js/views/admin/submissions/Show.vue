<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import SubmissionPointsTable from "@/components/public/assignments/SubmissionPointsTable.vue";
import SubmissionTestScenarios from "@/components/public/assignments/SubmissionTestScenarios.vue";
import SubmissionSubmittedFile from "@/components/public/assignments/SubmissionSubmittedFile.vue";

const submission = ref(null)

const route = useRoute()

onMounted(async () => {
    try {
        const res = await axios.get(`/api/submissions/${route.params.id}`)
        submission.value = res.data
    } catch(err) {
        alert('Chyba!')
        console.error(err)
    }
})

</script>

<template>
    <div v-if="submission">
        <h2 class="text-2xl font-bold text-center mb-10">Report: {{ submission.try }}. pokus</h2>

        <div v-if="submission.fail_messages" class="bg-red-600 py-5 px-10 text-center text-white mt-10">
            <h3>Zadanie nebolo ohodnotené kvôli nasledovnej chybe:</h3>
            <div class="text-xl font-bold mt-3">
                {{ submission.fail_messages.public_output }}
            </div>
        </div>

        <div v-else class="overflow-auto">
            <div class="border w-fit min-w-full border-primaryDark rounded-lg lg:px-10 lg:w-full">
                <SubmissionPointsTable :submission="submission" />
            </div>

            <hr class="my-20">

            <SubmissionTestScenarios :submission="submission" />

            <hr class="my-20">

            <SubmissionSubmittedFile :submission="submission" />
        </div>
    </div>
</template>
