<script setup>
import AppButton from '@/components/AppButton.vue';
import ManualSubmissionModal from '@/components/public/assignments/ManualSubmissionModal.vue';
import { computed, onMounted, ref, watch } from 'vue'
import SubmissionHistory from './SubmissionHistory.vue';
import { useAuthStore } from '@/stores/auth';
import VueCountdown from '@chenfengyuan/vue-countdown';
import { useProcessingAssignmentsStore } from '@/stores/processingAssignments'
import useEventsBus from "@/eventBus";
const { bus } = useEventsBus()

const authStore = useAuthStore()

const timeRemaining = new Date(props.assignment.deadline.raw) - new Date()

const isManualModalOpen = ref(false)

const processingAssignmentsStore = useProcessingAssignmentsStore()

const props = defineProps({
    assignment: Object
})

watch(() => bus.value.get('assignmentProcessed'), async () => {
    getSubmissionsCount()
    // processingAssignmentsStore.removeAssignment(props.assignment.id)
})

const submissionsCount = ref(null)

const getSubmissionsCount = async () => {
    try {
        const res = await axios.get(`/api/assignments/${props.assignment.id}/submissions-count`)
        submissionsCount.value = res.data
    } catch (err) {

    }
}

onMounted(() => {
    getSubmissionsCount()
})

const submitAssignment = async () => {
    try {
        processingAssignmentsStore.addAssignment(props.assignment.id)

        const res = await axios.post(`/api/assignments/${props.assignment.id}/submit`)
    } catch (err) {
        processingAssignmentsStore.removeAssignment(props.assignment.id)

        console.error(err)
        const res = err.response

        if (!res) {
            alert('Pri odovzdávaní nastala chyba')
            return
        }

        if (res.status === 400) {
            alert(res.data.message)
            return
        }

        alert('Pri odovzdávaní nastala chyba')
    }
}

const daysString = days => {
    if (days === 1) {
        return 'deň'
    } else if (days <= 4) {
        return 'dni'
    } else {
        return 'dní'
    }
}

const hoursString = hours => {
    if (hours === 1) {
        return 'hodina'
    } else if (hours > 1 && hours <= 4) {
        return 'hodiny'
    } else {
        return 'hodín'
    }
}

const minutesString = minutes => {
    if (minutes === 1) {
        return 'minúta'
    } else {
        return 'minút'
    }
}

const secondsString = seconds => {
    if (seconds === 1) {
        return 'sekunda'
    } else if (seconds > 1 && seconds <= 4) {
        return 'sekundy'
    } else {
        return 'sekúnd'
    }
}
</script>

<template>
    <div>
        <div v-if="assignment.is_after_deadline" class="text-center text-xl text-silver">
            Zadanie už nie je možné odovzdať
        </div>

        <div v-else-if="assignment.processing_user_submission || processingAssignmentsStore.assignments.includes(assignment?.id)" class="text-center text-xl text-silver">
            Spracúva sa odovzdanie...
        </div>

        <div v-else-if="submissionsCount >= assignment.tries?.length" class="text-center text-xl text-silver">
            Vyčerpali ste počet možností pre odovzdanie
        </div>

        <div v-else>
            <section class="text-center mb-20">
                <div class="text-xl text-sliver">Na odovzdanie zostáva:</div>

                <vue-countdown :time="timeRemaining" v-slot="{ days, hours, minutes, seconds }">
                    <div class="font-extrabold text-2xl">
                        {{ days }} {{ daysString(days) }} {{ hours }} {{ hoursString(hours) }} {{ minutes }} {{ minutesString(minutes) }} {{ seconds }} {{ secondsString(seconds) }}
                    </div>
                </vue-countdown>
            </section>

            <div v-if="authStore.loggedIn">
                <section class="text-center">
                    <div class="grid justify-center">
                        <AppButton @click="submitAssignment">Odovzdať cez GitHub</AppButton>
                        <button @click="isManualModalOpen = true" class="mt-4">Manuálne odovzdanie</button>
                    </div>

                    <ManualSubmissionModal
                        :isOpen="isManualModalOpen"
                        :assignmentId="assignment.id"
                        @submit="isManualModalOpen = false"
                        @closeModal="isManualModalOpen = false"
                    />
                </section>
            </div>

            <div v-else class="text-center">
                <div class="mb-5 text-sliver">
                    Pre odovzdanie je potrebné sa prihlásiť
                </div>

                <AppButton :to="{name: 'login'}" size="small">Prihlásiť</AppButton>
            </div>
        </div>

        <section v-if="authStore.loggedIn" class="mt-20">
            <SubmissionHistory :assignment="assignment" />
        </section>
    </div>
</template>
