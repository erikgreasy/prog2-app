<template>
    <form @submit.prevent="$emit('storeAssignment')">
        <div class="sm:col-span-2">
            <input type="text" v-model="assignment.title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Názov zadania" required>
        </div>

        <div>
            <input type="datetime-local" v-model="assignment.deadline" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Názov zadania" required>
        </div>

        <textarea v-model="assignment.excerpt"></textarea>

        <div>
            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
            <textarea v-model="assignment.content" id="description" rows="8" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Your description here"></textarea>
        </div>


        <div class="mt-10">
            <AppButton button>Uložiť</AppButton>
        </div>
    </form>
</template>

<script setup>
import AppButton from './AppButton.vue';

defineProps({
    assignment: {}
})
</script>
