<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

import PageHeader from '@/components/PageHeader.vue';
import { useAuthStore } from '@/stores/auth';
import VcsSection from '@/components/public/my-profile/VcsSection.vue'
import AssignmentCardFooter from '@/components/public/assignments/AssignmentCardFooter.vue';
import AssignmentCard from '@/components/public/assignments/AssignmentCard.vue';
import { useAssignmentsPublished } from '@/composables/assignmentsPublished';
import ShowMoreLink from '@/components/public/ShowMoreLink.vue';

const authStore = useAuthStore()
const currentAssignment = ref(null)
const { assignments, getAssignments }  = useAssignmentsPublished()
const submissions = ref([])

const connectGithub = () => {
    alert('connect github')
}

const maximumTooltipVisible = ref(false)
const zapocetTooltipVisible = ref(false)
const pointsTooltipVisible = ref(false)

const getSubmissions = async () => {
    try {
        const res = await axios.get('/api/my-submissions')
        console.log(res)
        submissions.value = res.data
    } catch (err) {
        alert('err')
        console.error(err)
    }
}

const getCurrentAssignemnt = async () => {
    try {
        const res = await axios.get('/api/assignments/current')
        console.log(res)
        currentAssignment.value = res.data
    } catch(err) {
        const res = err.response

        if(res.status === 500) {
            alert('Nepodarilo sa načítať aktuálne zadanie')
            console.error(err)
        }
    }
}

const getFinalSubmission = assignmentId => {
    return submissions.value.find(item => item.assignment_id == assignmentId && item.is_final)
}

const studentPoints = computed(() => {
    return submissions.value
        .filter(submission => submission.is_final)
        .reduce((partialSum, a) => partialSum + a.points, 0)
})

onMounted(async () => {
    getAssignments()
    getCurrentAssignemnt()
    getSubmissions()
})
</script>

<template>
    <div>
        <PageHeader>
            {{ authStore.user.full_name }}
        </PageHeader>

        <div class="container my-20">
            <section class="lg:w-2/3 lg:mx-auto">
                <h2 class=" font-extrabold text-center text-3xl mb-8">Aktuálne zadanie</h2>

                <AssignmentCard v-if="currentAssignment" :assignment="currentAssignment" :minimalLayout="true" />

                    <!-- {{ currentAssignment.deadline }}

                    <RouterLink :to="{name: 'assignments.show', params: {slug: currentAssignment.slug}}">
                        Zobraziť
                    </RouterLink> -->

                <div v-else class="text-sliver text-lg mt-3 text-center">
                    Momentálne nie je aktívne žiadne zadanie
                </div>
            </section>

            <hr class="my-20">

            <section class="">
                <h2 class="text-center font-extrabold text-3xl mb-10">Hodnotenie</h2>

                <!-- visualization -->
                <div class="lg:w-2/3 mx-auto mb-20">
                    <div class="h-10 relative">
                        <div
                            @mouseover="maximumTooltipVisible = true"
                            @mouseleave="maximumTooltipVisible = false"
                            class="h-full bg-[#D9D9D9] rounded-lg"
                        ></div>
                        <div
                            @mouseover="zapocetTooltipVisible = true"
                            @mouseleave="zapocetTooltipVisible = false"
                            class="bg-[#FFBEBE] absolute top-0 left-0 h-full rounded-lg" :style="`width: ${30/60*100}%;`"
                        >
                            <div v-if="zapocetTooltipVisible" class="text-sm absolute right-0 -top-2 -translate-y-full bg-black bg-opacity-90 px-2 rounded text-white">
                                Hranica pre zápočet: 30b
                            </div>
                        </div>

                        <div
                            @mouseover="pointsTooltipVisible = true"
                            @mouseleave="pointsTooltipVisible = false"
                            class="bg-[#D2FCD1] absolute top-0 left-0 h-full rounded-lg flex items-center pl-5" :style="`width: ${studentPoints / 60 * 100}%;`"
                        >
                            <span class="font-semibold">{{ studentPoints }}b</span>
                            <div v-if="pointsTooltipVisible" class="whitespace-nowrap text-sm absolute right-0 -top-2 -translate-y-full bg-black bg-opacity-90 px-2 rounded text-white">
                                Získané body: {{ studentPoints }}b
                            </div>
                        </div>

                        <div v-if="maximumTooltipVisible" class="text-sm absolute right-0 -top-2 -translate-y-full bg-black bg-opacity-90 px-2 rounded text-white">
                            Maximum: 60b
                        </div>
                    </div>

                    <div class="text-center mt-2">
                        Zápočet: min. 30b
                    </div>
                </div>

                <!-- submissions -->
                <div class="lg:w-fit mx-auto grid gap-y-14">
                    <article v-for="assignment in assignments">
                        <template v-if="getFinalSubmission(assignment.id)">
                            <h3 class="font-semibold text-xl mb-2">
                                <RouterLink :to="{name: 'assignments.show', params: {slug: assignment.slug}}">
                                    {{ assignment.title }}
                                </RouterLink>
                            </h3>

                            <div class="flex gap-x-10">
                                <div>
                                    Finálny pokus: <span class="font-bold text-primary">{{ getFinalSubmission(assignment.id)?.try }}</span>
                                </div>

                                <div>
                                    Hodnotenie: <span class="font-bold text-primary">{{ getFinalSubmission(assignment.id)?.points }}/{{ assignment.points.raw }}</span>
                                </div>

                                <ShowMoreLink :to="{name: 'assignments.submissions.show', params: {slug: assignment.slug, submission_id: getFinalSubmission(assignment.id).id}}">
                                    Zobraziť pokus
                                </ShowMoreLink>
                            </div>
                        </template>
                    </article>
                </div>
            </section>
        </div>
    </div>
</template>
