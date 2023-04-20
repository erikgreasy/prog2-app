<script setup>
import CompilationStats from "@/components/public/assignments/CompilationStats.vue";
import PrismCode from "@/components/PrismCode.vue";

defineProps({
    resultCase: Object,
    index: Number,
})
</script>

<template>
    <div
        class="mb-4 mt-10 border border-[#9a9a9a]"
    >
        <div class="p-5 bg-[#f7f7f7]">
            {{ index + 1 }}. Prípad
            <span v-if="resultCase.is_success" class="bg-green-400 py-0.5 px-1 rounded-sm font-semibold text-white">OK</span>
            <span v-else class="bg-red-400 py-0.5 px-1 rounded-sm font-semibold text-white">FAIL</span>
        </div>

        <CompilationStats
            :build-status="resultCase.build_status"
            :gcc-errors="resultCase.gcc_errors"
            :gcc-warnings="resultCase.gcc_warnings"
        />

        <div class="my-10 grid gap-y-10 text-center">
            <div v-if="!resultCase.build_status" class="text-2xl text-red-800">
                Zadanie nebolo možné pre daný prípad skompilovať
            </div>
            <div v-if="resultCase.gcc_errors?.length">
                <h3>
                    <span class="bg-red-300 px-2 py-1 rounded">
                        GCC errors (počet chýb: {{ resultCase.gcc_errors?.length }})
                    </span>
                </h3>
                <ul>
                    <li v-for="(error, index) in resultCase.gcc_errors" :key="index">
                        <code>{{ error }}</code>
                    </li>
                </ul>
            </div>

            <div v-if="resultCase.gcc_warnings?.length">
                <h3>
                    <span class="bg-orange-300 px-2 py-1 rounded">
                        GCC warnings (počet upozornení: {{ resultCase.gcc_warnings?.length }})
                    </span>
                </h3>
                <ul>
                    <li v-for="(warning, index) in resultCase.gcc_warnings" :key="index">
                        <code>{{ warning }}</code>
                    </li>
                </ul>
            </div>
        </div>

        <div v-if="resultCase.gcc_macro_defs" class="grid grid-cols-4 gap-x-10 py-5 px-10 items-center">
            <div class="justify-self-end">
                <i class="fa-solid fa-hammer"></i> kompilácia:
            </div>
            <div class="col-span-3">
                <PrismCode>{{ resultCase.gcc_macro_defs }}</PrismCode>
            </div>
        </div>

        <div v-if="resultCase.build_status">
            <div v-if="resultCase.cmdin" class="grid grid-cols-4 gap-x-10 py-5 px-10 items-center">
                <div class="justify-self-end">
                    CMD IN:
                </div>
                <div class="col-span-3">
                    <PrismCode>{{ resultCase.cmdin }}</PrismCode>
                </div>
            </div>

            <div v-if="resultCase.stdin" class="grid grid-cols-4 gap-x-10 py-5 px-10 items-center">
                <div class="justify-self-end">
                    <i class="fas fa-keyboard" aria-hidden="true"></i> stdin:
                </div>

                <div class="col-span-3">
                    <PrismCode>{{ resultCase.stdin }}</PrismCode>
                </div>
            </div>

            <div class="grid grid-cols-4 gap-x-10 py-5 px-10 items-center">
                <div class="justify-self-end">
                    stdout:
                </div>
                <div class="col-span-3">
                    <PrismCode :useLineNumbers="true">{{ resultCase.actual_stdout }}</PrismCode>
                    <p class="mb-2 text-xs">Počet zobrazených riadkov je obmedzený na počet riadkov správneho výpisu. Maximálna dĺžka vypísaného riadku je 500 znakov.</p>
                </div>
            </div>

            <div class="grid grid-cols-4 gap-x-10 py-5 px-10 items-center">
                <div class="justify-self-end">
                    Správny stdout:
                </div>
                <div class="col-span-3">
                    <PrismCode :useLineNumbers="true">{{ resultCase.stdout }}</PrismCode>
                </div>
            </div>
        </div>
    </div>
</template>
