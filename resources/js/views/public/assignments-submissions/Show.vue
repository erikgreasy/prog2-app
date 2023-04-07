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
        <h2 class="text-2xl font-bold text-center">Report: {{ submission.try }}. pokus</h2>

        <div v-if="submission.fail_messages" class="bg-red-600 py-5 px-10 text-center text-white mt-10">
            <h3>Zadanie nebolo ohodnotené kvôli nasledovnej chybe:</h3>
            <div class="text-xl font-bold mt-3">
                {{ submission.fail_messages.public_output }}
            </div>
        </div>

        <div v-else>
            <div class="flex justify-center gap-x-5 text-sm mt-10 mb-20">
                <div 
                    :class="{
                        'bg-green-200': submission.build_status,
                        'bg-red-300': !submission.build_status
                    }"
                    class="rounded-lg  py-1.5 px-5"
                >
                    Build status: {{ submission.build_status ? 'OK' : 'FAIL' }}
                </div>

                <div
                    :class="{
                        'bg-green-200': !submission.gcc_warning?.length,
                        'bg-orange-300': submission.gcc_warning?.length
                    }"
                    class="rounded-lg py-1.5 px-5"
                >
                    GCC warnings: {{ submission.gcc_warning?.length || '0' }}
                </div>

                <div
                    :class="{
                        'bg-green-200': !submission.gcc_error?.length,
                        'bg-red-300': submission.gcc_error?.length
                    }"
                    class="rounded-lg py-1.5 px-5"
                >
                    GCC errors: {{ submission.gcc_error?.length || '0' }}
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
                            <td class="px-10 py-3" colspan="2">Získané body pred penalizáciou:</td>
                            <td class="px-10 py-3 text-center font-bold">
                                {{ submission.points_before_penalisation }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-10 py-3" colspan="2">Získané body po penalizácii:</td>
                            <td class="px-10 py-3" align="center">
                                {{ submission.points }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-10 py-3 font-bold" colspan="2">Body zapísané do AIS-u:</td>
                            <td class="px-10 py-3" align="center">
                                <span class="py-1 px-2 bg-primary text-white rounded font-bold">
                                    {{ submission.points }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-10 grid gap-y-10">
                <div v-if="!submission.build_status" class="bg-red-600 text-white py-5 px-10 text-center font-bold text-xl">
                    Vaše zadanie nie je kompilovateľné kompilátorom GCC.
                </div>
        
                <div v-if="submission.gcc_error?.length">
                    <h3>
                        GCC errors (počet chýb: {{ submission.gcc_error?.length }})
                    </h3>
                    <ul>
                        <li v-for="(error, index) in submission.gcc_error" :key="index">
                            <code>{{ error }}</code>
                        </li>
                    </ul>
                </div>
    
                <div v-if="submission.gcc_warning?.length">
                    <h3>
                        GCC warnings (počet upozornení: {{ submission.gcc_warning?.length }})
                    </h3>
                    <ul>
                        <li v-for="(warning, index) in submission.gcc_warning" :key="index">
                            <code>{{ warning }}</code>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="my-20">
        
            <div>
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

                        <svg 
                            :class="{'rotate-180': index === accordionIndex}"
                            class="h-6 w-6 transition"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
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
                                <PrismCode :useLineNumbers="true">{{ resultCase.actual_stdout }}</PrismCode>
                                <p class="mb-2 text-xs">Počet zobrazených riadkov je obmedzený na počet riadkov správneho výpisu. Maximálna dĺžka vypísaného riadku je 500 znakov.</p>
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
    
            <hr class="my-20">

            <div>
                <a :href="submission.file_path" target="_blank">Odovzdany subor</a>
                <PrismCode lang="cpp" :showInvisibles="false" :useLineNumbers="true">
                    {{ submission.file_content }}
                </PrismCode>
            </div>
        </div>
    </div>
</template>