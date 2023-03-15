<template>
    <div>
        <form v-if="assignment">
            <InputGroup>
                <AppInput 
                    v-model="assignment.title"
                    :errors="errors.title"
                    @change="slugifyTitle"
                    placeholder="Názov zadania"
                    additional-classes="!bg-white !text-xl"
                />
            </InputGroup>
    
            <AdminCard>
                <div class="mb-5">
                    <InputWithError label="Slug:" :errors="errors?.slug">
                        <AppInput @change="transformSlug" v-model="assignment.slug" :errors="errors?.slug" placeholder="Slug" />
                    </InputWithError>
                </div>
        
                <div class="mb-5">
                    <InputWithError label="Deadline:" :errors="errors?.deadline">
                        <AppInput type="datetime-local" v-model="assignment.deadline" :errors="errors?.deadline" />
                    </InputWithError>
                </div>
        
                <div class="mb-5">
                    <InputWithError label="Krátky popis:" :errors="errors?.excerpt">
                        <AppTextarea v-model="assignment.excerpt" placeholder="Krátky popis" :errors="errors?.excerpt" />
                    </InputWithError>
                </div>
        
                <div>
                    <InputLabel>Obsah:</InputLabel>
                    <ContentEditor
                        ref="contentEditor"
                        name="content"
                        :content="assignment.content"
                        @processed="data => assignment.content = data"
                    />
                </div>
            </AdminCard>
        </form>

        <div class="mt-5">
            <h2>Odovzdanie</h2>
    
            <AdminCard>
                <ContentEditor 
                    ref="instructionsEditor" 
                    name="submission_instructions" 
                    :content="assignment.submission_instructions"
                    @processed="data => assignment.submission_instructions = data" 
                />

                <!-- <AppTextarea v-model="assignment.submission_instructions" placeholder="Inštrukcie pre odovzdanie" :errors="errors?.submission_instructions"></AppTextarea> -->
                <!-- <ContentEditor name="submission_instructions" /> -->
            </AdminCard>
        </div>

        <div class="mt-5">
            <h2>Materiály</h2>

            <AdminCard>
                <div v-for="(material, index) in assignment.materials" :key="index">
                    <InputWithError :errors="errors[`materials.${index}.src`]">
                        <div class="flex items-center gap-x-3 w-full">
                            <AppInput v-model="material.src" :errors="errors[`materials.${index}.src`]" />
                            <button @click="removeMaterial(index)" type="button" class="text-xl">&times;</button>
                        </div>
                    </InputWithError>
                </div>

                <AppButton @click="addMaterial" size="small">Pridať materiál</AppButton>
            </AdminCard>
        </div>
    </div>
</template>

<script setup>
import ContentEditor from './admin/assignments/ContentEditor.vue'
import { inject, ref, watch } from 'vue';
import AdminCard from './AdminCard.vue';
import InputGroup from './admin/forms/InputGroup.vue';
import AppInput from '@/components/admin/forms/AppInput.vue'
import AppTextarea from './admin/forms/AppTextarea.vue';
import InputLabel from './admin/forms/InputLabel.vue';
import InputWithError from './admin/forms/InputWithError.vue';
import AppButton from '@/components/AppButton.vue';
import slugify from 'slugify'
import useEventsBus from '@/eventBus';

const assignment = inject('assignment')

const props = defineProps({
    errors: Object
})

const emit = defineEmits(['processed'])

const { bus } = useEventsBus()

const contentEditor = ref()
const instructionsEditor = ref()

watch(() => bus.value.get('storingAssignment'), async () => {
    assignment.value.content = await contentEditor.value.save()
    assignment.value.submission_instructions = await instructionsEditor.value.save()

    emit('processed')
})

const addMaterial = () => {
    assignment.value.materials.push({})
}

const removeMaterial = index => {
    assignment.value.materials.splice(index, 1)
}

const slugifyTitle = () => {
    if (assignment.value.slug?.length) {
        return
    }
    
    assignment.value.slug = slugify(assignment.value.title).toLowerCase()
}

const transformSlug = () => {
    assignment.value.slug = slugify(assignment.value.slug).toLowerCase()
}

</script>
