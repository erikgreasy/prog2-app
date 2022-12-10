<template>
    <form>
        <!-- <div class="sm:col-span-2 mb-5"> -->
            <AppInput 
                v-model="assignment.title"
                :errors="errors.title"
                placeholder="Názov zadania"
                additional-classes="!bg-white !text-xl"
            />
            <!-- <input type="text" 
                
                class="bg-white border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                
            > -->
        <!-- </div> -->

        <AdminCard>
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Slug:</label>
                <AppInput v-model="assignment.slug" :errors="errors?.slug" placeholder="Slug" />
            </div>
            <!-- <div class="mb-5">
                <input type="text" v-model="assignment.slug" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Slug">
                <p v-if="errors.slug" class="mt-2 text-xs text-red-600 dark:text-red-500"><span class="font-medium">Oh, snapp!</span> Some error message.</p>
            </div> -->
    
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deadline</label>
                <input type="datetime-local" v-model="assignment.deadline" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Názov zadania">
            </div>
    
            <div class="mb-5">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Krátky popis</label>
                <textarea 
                    v-model="assignment.excerpt" 
                    id="description" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Your description here"></textarea>
            </div>
    
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Obsah</label>
                <ContentEditor v-if="assignment.content" />
            </div>
        </AdminCard>
    </form>
</template>

<script setup>
import ContentEditor from './admin/assignments/ContentEditor.vue'
import { computed, inject } from 'vue';
import AdminCard from './AdminCard.vue';
import InputGroup from './admin/forms/InputGroup.vue';
import AppInput from '@/components/admin/forms/AppInput.vue'

const assignment = inject('assignment')

const props = defineProps({
    errors: Object
})

</script>
