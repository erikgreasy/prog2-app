<script setup>
import { onMounted, ref, watch } from "vue";

import useEventsBus from "@/eventBus";

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
            <li v-for="submission in submissions" :key="submission.id" class="pt-4 mb-6">
                <div class="text-sliver mb-2">
                    <span class="h-4 w-4 rounded-full bg-[#D9D9D9] inline-block absolute left-0 -translate-x-1/2 translate-y-1"></span>
                    {{ submission.created_at }}
                </div>

                <div class="mb-3">
                    <div class="flex items-center">
                        <span class="text-xl font-bold">
                            <router-link :to="{name: 'assignments.submissions.show', params: {slug: assignment.slug, submission_id: submission.id}}">
                                <span v-if="submission.status == 'failed'">Neúspešný pokus</span>
                                <span v-else>Pokus č.{{ submission.try }}</span>
                            </router-link>
                        </span>
                        <div v-if="submission.status === 'completed'" class="py-0.5 px-2 font-bold ml-4 inline-block bg-primary text-white rounded-lg">{{ submission.points }}/{{ assignment.points.raw }}</div>
                        <div v-else-if="submission.status === 'failed'" class="py-0.5 px-2 font-bold ml-4 inline-block bg-red-600 text-white rounded-lg">Neúspešné</div>
                    </div>

                    <div class="text-sm">
                        <span v-if="submission.source === 'manual'">Manuálne odovzdanie</span>
                        <span v-else-if="submission.source === 'vcs'">GitHub odovzdanie</span>
                    </div>
                </div>

                <router-link :to="{name: 'assignments.submissions.show', params: {id: 1, submission_id: submission.id}}" class="inline-flex items-center text-sm gap-x-3">
                    Zobraziť pokus
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.0253 4.94168L17.0837 10L12.0253 15.0583M2.91699 10H16.942" stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </router-link>
            </li>
        </ul>

        <div v-else class="text-center">
            Zatiaľ žiadne odovzdania
        </div>
    </div>
</template>
