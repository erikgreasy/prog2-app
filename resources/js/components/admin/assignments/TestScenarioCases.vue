<script setup>
import AppTextarea from '@/components/admin/forms/AppTextarea.vue'
import AppButton from "@/components/AppButton.vue";
import InputWithError from "@/components/admin/forms/InputWithError.vue";
import { ref } from 'vue';

defineProps({
    cases: {
        type: Array
    },
    visible: Boolean
})

const newCase = ref({})
const addNewCase = ref(false)

const errors = ref({})
</script>

<template>
    <div v-show="visible">
        <hr class="mb-10">

        <div v-for="(testCase, index) in cases" :key="testCase.id">
            <h4 class="text-xl font-semibold mb-3">Case {{ ++index }}</h4>
            <div class="grid grid-cols-4 gap-x-2">
                <InputWithError label="CMD IN:" :errors="errors?.title">
                    <AppTextarea v-model="testCase.cmdin" disabled/>
                </InputWithError>

                <InputWithError label="STD IN:" :errors="errors?.title">
                    <AppTextarea v-model="testCase.stdin" disabled/>
                </InputWithError>

                <InputWithError label="STD OUT:" :errors="errors?.title">
                    <AppTextarea v-model="testCase.stdout" disabled/>
                </InputWithError>

                <InputWithError label="ERR OUT:" :errors="errors?.title">
                    <AppTextarea v-model="testCase.errout" disabled/>
                </InputWithError>
            </div>

            <div class="flex justify-end gap-x-3">
                <AppButton size="xs">Edit</AppButton>
                <AppButton size="xs">Delete</AppButton>
            </div>
            <hr class="my-10">
        </div>

        <div v-if="addNewCase">
            <h4 class="text-xl font-semibold mb-3">Case {{ cases.length + 1 }}</h4>
            <div class="grid grid-cols-4 gap-x-2">
                <InputWithError label="CMD IN:" :errors="errors?.title">
                    <AppTextarea v-model="newCase.cmdin"/>
                </InputWithError>

                <InputWithError label="STD IN:" :errors="errors?.title">
                    <AppTextarea v-model="newCase.stdin"/>
                </InputWithError>

                <InputWithError label="STD OUT:" :errors="errors?.title">
                    <AppTextarea v-model="newCase.stdout"/>
                </InputWithError>

                <InputWithError label="ERR OUT:" :errors="errors?.title">
                    <AppTextarea v-model="newCase.errout"/>
                </InputWithError>
            </div>
            <div class="flex justify-end gap-x-3">
                <AppButton size="xs">Save</AppButton>
            </div>
            <hr class="my-10">
        </div>

        <div class="text-center pt-10">
            <AppButton @click="addNewCase = true" size="xs">
                Pridať case
            </AppButton>
        </div>
    </div>
</template>
