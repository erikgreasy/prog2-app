<script setup>
import SubmissionTestCase from "@/components/public/assignments/SubmissionTestCase.vue";

defineProps({
    id: Number,
    resultScenario: Object,
    isOpen: Boolean,
})

defineEmits([
    'toggle',
])
</script>

<template>
    <div
         class="mb-10"
         :id="`scenario_${id}`"
    >
        <div
            @click="$emit('toggle', id)"
            class="border-b-2 cursor-pointer py-2 flex justify-between items-center"
            :class="{'border-red-400' : !resultScenario.is_success, 'border-green-400' : resultScenario.is_success}"
        >
            <div>
                <span class="text-xl font-bold">
                    {{ id + 1 }}. Testovací scenár
                </span>
<!--                {{ resultScenario.is_success ? 'OK' : 'FAIL' }}-->
            </div>

            <div class="flex items-center gap-x-3">
                {{ resultScenario.scenario.title }}

                <div
                    class="px-2 py-2 font-bold border border-[#c9c9c9]"
                    :class="{'border-[#bc3e00] bg-[#ffe9dc] text-[#bc3e00]': !resultScenario.is_success}"
                >
                    {{ resultScenario.points }} / {{ resultScenario.scenario.points }}
                </div>

                <svg
                    :class="{'rotate-180': isOpen}"
                    class="h-6 w-6 transition"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        <div v-show="isOpen">
            <SubmissionTestCase
                v-for="resultCase in resultScenario.result_cases"
                :key="resultCase.id"
                :result-case="resultCase"
            />
        </div>
    </div>
</template>
