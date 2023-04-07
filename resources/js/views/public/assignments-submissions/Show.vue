<script setup>
import AssignmentHeader from "@/components/public/assignments/AssignmentHeader.vue";
import { useAssignments } from "@/composables/assignments";
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import PrismCode from '@/components/PrismCode.vue'

const route = useRoute()

const accordionIndex = ref(null)

const assignment = ref({})
const currentSection = ref('content')
  
const getAssignment = async () => {
    const res = await axios.get(`/api/assignments/slug/${route.params.slug}`)
    assignment.value = res.data
}

const submission = ref(null)

onMounted(async () => {
    await getAssignment()
    
    const res = await axios.get(`/api/assignments/${assignment.value.id}/submissions/${route.params.index}`)
    submission.value = res.data
    console.log(res)
})

const togglAccordion = index => {
    if (accordionIndex.value === index) {
        accordionIndex.value = null

        return
    }

    accordionIndex.value = index
}
</script>

<template>
    <div class="container" v-if="submission">
        <h2 class="text-2xl font-bold text-center">Report: 1. pokus</h2>

        <div class="flex justify-center gap-x-5 text-sm mt-10 mb-20">
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

        <div class="border border-primaryDark rounded-lg px-10">
            <table class="w-full">
                <thead>
                    <tr class="px-5">
                        <th class="px-10 py-3">Scenár</th>
                        <th class="px-10 py-3">Stav</th>
                        <th class="px-10 py-3">Získané body</th>
                        <th class="px-10 py-3">Max. počet bodov</th>
                    </tr>
                </thead>

                <tbody>
                    <tr 
                        v-for="(resultScenario, index) in submission?.result_scenarios"
                        :key="resultScenario.id"
                        :class="{'bg-red-200': !resultScenario.is_success}"
                    >
                        <td class="px-10 py-3 border-b border-[#E0E0E0]">
                            <a :href="`#scenario_${index}`">
                                {{ resultScenario.scenario.title }}
                            </a>
                        </td>
                        <td class="px-10 py-3 border-b border-[#E0E0E0]" align="center">
                            {{ resultScenario.is_success ? 'OK' : 'FAIL' }}
                        </td>
                        <td class="px-10 py-3 border-b border-[#E0E0E0]" align="center">
                            {{ resultScenario.points }}
                        </td>
                        <td class="px-10 py-3 border-b border-[#E0E0E0]" align="center">
                            {{ resultScenario.scenario.points }}
                        </td>
                    </tr>
                </tbody>

                <tfoot>
                    <tr>
                        <td class="px-10 py-3" colspan="2">Počet bodov bez penalizácia:</td>
                        <td class="px-10 py-3 text-center font-bold">
                            {{ submission.points }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-10 py-3" colspan="2">Získaný počet bodov:</td>
                        <td class="px-10 py-3" align="center">
                            {{ submission.points }} <span class="bg-primary text-white">Zápis do AIS</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-32">
            <div v-for="(resultScenario, index) in submission?.result_scenarios" 
                :key="resultScenario.id" 
                class="mb-10"
                :id="`scenario_${index}`"
            >
                <div
                    @click="togglAccordion(index)"
                    class="border-b-2 cursor-pointer py-4"
                    :class="{'border-red-400' : !resultScenario.is_success, 'border-green-400' : resultScenario.is_success}"
                >
                    <span class="text-xl font-bold">
                        {{ ++index }}. Testovací scenár
                    </span>
                    {{ resultScenario.scenario.title }} 
                    {{ resultScenario.is_success ? 'OK' : 'FAIL' }}
                    {{ resultScenario.points }} / {{ resultScenario.scenario.points }}
                </div>
                <div 
                    v-show="index === accordionIndex"
                    v-for="resultCase in resultScenario.result_cases"
                    :key="resultCase.id"
                    class="mb-4 mt-10 border border-[#9a9a9a]"
                >
                    <div class="p-5 bg-[#f7f7f7]">
                        Pripad: 
                        <span v-if="resultCase.is_success" class="bg-green-400 py-0.5 px-1 rounded-sm font-semibold text-white">OK</span>
                        <span v-else class="bg-red-400 py-0.5 px-1 rounded-sm font-semibold text-white">FAIL</span>
                    </div>

                    <div class="grid grid-cols-4 gap-x-10 py-5 px-10 items-center">
                        <div class="justify-self-end">
                            CMD IN:
                        </div>
                        <div class="col-span-3">
                            <PrismCode>1 2 3</PrismCode>
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-x-10 py-5 px-10 items-center">
                        <div class="justify-self-end">
                            STDIN:
                        </div>

                        <div class="col-span-3">
                            <PrismCode>{{ resultCase.stdin }}</PrismCode>
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-x-10 py-5 px-10 items-center">
                        <div class="justify-self-end">
                            STDOUT:
                        </div>
                        <div class="col-span-3">
                            <p class="mb-2 text-sm">Počet zobrazených riadkov je obmedzený na počet riadkov správneho výpisu. Maximálna dĺžka vypísaného riadku je 500 znakov.</p>
                            <PrismCode :useLineNumbers="true">{{ resultCase.actual_stdout }}</PrismCode>
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-x-10 py-5 px-10 items-center">
                        <div class="justify-self-end">
                            SPRAVNY STDOUT:
                        </div>
                        <div class="col-span-3">
                            <PrismCode :useLineNumbers="true">{{ resultCase.stdout }}</PrismCode>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <a :href="submission.file_path" target="_blank">Odovzdany subor</a>
            <PrismCode lang="cpp" :showInvisibles="false" :useLineNumbers="true">
                {{ submission.file_content }}
            </PrismCode>
        </div>
    </div>
</template>