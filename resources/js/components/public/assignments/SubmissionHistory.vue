<script setup>
import { onMounted, ref, watch } from "vue";

import useEventsBus from "@/eventBus";
import SubmissionHistoryItem from "@/components/public/assignments/SubmissionHistoryItem.vue";

const { bus } = useEventsBus()

const props = defineProps({
    assignment: Object,
})

const loading = ref(false)

const submissions = ref([])

const getSubmissions = async () => {
    try {
        loading.value = true
        const res = await axios.get(`/api/assignments/${props.assignment.id}/submissions`)
        submissions.value = res.data
    } catch(err) {
        alert('error!')
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    getSubmissions()
})


watch(() => bus.value.get('assignmentProcessed'), async () => {
    getSubmissions()
})

</script>

<template>
    <div>
        <h3 class="font-semibold text-primary text-2xl mb-10 text-center">História odovzdávania</h3>

        <div v-if="loading">
            Loading...
        </div>

        <ul
            v-else-if="submissions.length"
            class="pl-8 border-l relative border-gray-[#D9D9D9] md:w-fit md:mx-auto"
        >
            <SubmissionHistoryItem
                v-for="submission in submissions"
                :key="submission.id"
                :submission="submission"
                :assignment="assignment"
            />
        </ul>

        <div v-else class="text-center">
            Zatiaľ žiadne odovzdania
        </div>
    </div>
</template>
