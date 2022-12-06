<template>
    <form @submit.prevent="submitForm">
        <div class="sm:col-span-2">
            <input type="text" v-model="assignment.title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Názov zadania">
        </div>

        <div>
            <input type="text" v-model="assignment.slug" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Slug">
        </div>

        <div>
            <input type="datetime-local" v-model="assignment.deadline" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Názov zadania">
        </div>

        <div>
            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Krátky popis</label>
            <textarea 
                v-model="assignment.excerpt" 
                id="description" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Your description here"></textarea>
        </div>

        <ContentEditor v-if="assignment.content" />

        <div class="mt-10">
            <AppButton button>Uložiť</AppButton>
        </div>
    </form>
</template>

<script setup>
import AppButton from './AppButton.vue';
import ContentEditor from './admin/assignments/ContentEditor.vue'
import { inject, onMounted, watch } from 'vue';
import useEventsBus from '@/eventBus.js'

const assignment = inject('assignment')

const { emit, bus } = useEventsBus()

const componentEmits = defineEmits(['storeAssignment'])

const submitForm = () => {
    // emit event to get editor data
    emit('storingAssignment')



    // enjoy!
}

watch(() => bus.value.get('contentEditor'), async contentPromise => {
    const outputData = await contentPromise[0]

    assignment.value.content = outputData

    // after receiving, emit event to send request
    componentEmits('storeAssignment')
})

</script>
