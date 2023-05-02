<script setup>
defineProps({
    submission: Object,
})

const openScenario = id => {
    document.querySelector(id + ' > div').click()
}
</script>

<template>
    <table class="w-full">
        <thead>
        <tr class="px-5">
            <th class="px-5 lg:px-10 py-3">Scenár</th>
            <th class="px-5 lg:px-10 py-3">Stav</th>
            <th class="px-5 lg:px-10 py-3">Získané body</th>
            <th class="px-5 lg:px-10 py-3">Max. počet bodov</th>
        </tr>
        </thead>

        <tbody>
        <tr
            v-for="(resultScenario, index) in submission?.result_scenarios"
            :key="resultScenario.id"
        >
            <td class="px-5 lg:px-10 py-3 border-b border-[#E0E0E0]">
                <a
                    :href="`#scenario_${index}`"
                    :class="resultScenario.is_success ? 'text-green-600' : 'text-red-600'"
                    @click="openScenario(`#scenario_${index}`)"
                >
                    {{ resultScenario.scenario.title }}
                </a>
            </td>
            <td
                class="px-5 lg:px-10 py-3 border-b border-[#E0E0E0]" align="center"
                :class="resultScenario.is_success ? 'text-green-600' : 'text-red-600'"
            >
                {{ resultScenario.is_success ? 'OK' : 'FAIL' }}
            </td>
            <td class="px-5 lg:px-10 py-3 border-b border-[#E0E0E0]" align="center">
                {{ resultScenario.points }}
            </td>
            <td class="px-5 lg:px-10 py-3 border-b border-[#E0E0E0]" align="center">
                {{ resultScenario.scenario.points }}
            </td>
        </tr>
        </tbody>

        <tfoot>
        <tr>
            <td class="px-5 lg:px-10 py-3" colspan="2">Získané body pred penalizáciou:</td>
            <td class="px-5 lg:px-10 py-3 text-center font-bold">
                {{ submission.points_before_penalisation }}
            </td>
        </tr>
        <tr>
            <td class="px-5 lg:px-10 py-3" colspan="2">Získané body po penalizácii:</td>
            <td class="px-5 lg:px-10 py-3" align="center">
                {{ submission.points }}
            </td>
        </tr>
        <tr>
            <td class="px-5 lg:px-10 py-3 font-bold" colspan="2">Body zapísané do AIS-u:</td>
            <td class="px-5 lg:px-10 py-3" align="center">
                <span class="py-1 px-2 bg-primary text-white rounded font-bold">
                    {{ submission.points }}
                </span>
            </td>
        </tr>
        </tfoot>
    </table>
</template>
