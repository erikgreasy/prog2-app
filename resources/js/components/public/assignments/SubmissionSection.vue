<script setup>
import AppButton from '@/components/AppButton.vue';
import ManualSubmissionModal from '@/components/public/assignments/ManualSubmissionModal.vue';
import { computed, ref } from 'vue'
import SubmissionHistory from './SubmissionHistory.vue';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore()

const isManualModalOpen = ref(false)

const props = defineProps({
    assignment: Object
})

const submitAssignment = async () => {
    const res = await axios.post(`/api/assignments/${props.assignment.id}/submit`)
    console.log(res)
}
</script>

<template>
    <div>
        <section class="text-center mb-20">
            <div class="text-xl text-sliver">Na odovzdanie zostáva:</div>
            <div class="font-extrabold text-2xl">12 hodín 38 minút 10 sekúnd</div>
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
    
            <section class="grid grid-cols-3 mt-20">
                <div class="border-r border-[#D1D1D1]">
                    <SubmissionHistory :assignmentId="assignment.id" />
                </div>
    
                <div class="editorjs-parser col-span-2 pl-10" v-html="assignment.submission_instructions"></div>
            </section>
        </div>

        <div v-else class="text-center">
            <div class="mb-5 text-sliver">
                Pre odovzdanie je potrebné sa prihlásiť
            </div>

            <AppButton :to="{name: 'login'}" size="small">Prihlásiť</AppButton>
        </div>
    </div>
</template>
