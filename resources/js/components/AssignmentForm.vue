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
const materialsEditor = ref()

watch(() => bus.value.get('storingAssignment'), async () => {
    assignment.value.content = await contentEditor.value.save()
    assignment.value.submission_instructions = await instructionsEditor.value.save()
    assignment.value.materials = await materialsEditor.value.save()

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

const removeTry = index => {
    assignment.value.tries.splice(index, 1)
}

const addTry = () => {
    if (!assignment.value.tries) {
        assignment.value.tries = []
    }

    assignment.value.tries.push({})
}
</script>

<template>
    <div class="grid grid-cols-12 gap-8 items-start">
        <div class="col-span-9">
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
                        <ContentEditor
                            ref="materialsEditor"
                            name="materials"
                            :content="assignment.materials"
                            @processed="data => assignment.materials = data"
                        />
                    </AdminCard>
                </div>

                <div class="mt-5">
                    <h2>Max. body podľa pokusu</h2>
                    <AdminCard>
                        <div v-for="(assignmentTry, index) in assignment.tries" :key="index">
                            <InputWithError :label="`Pokus ${index + 1}`" :errors="errors?.[`tries.${index}.max_points`]">
                                <div class="flex items-center gap-x-5">
                                    <AppInput type="number" step="0.5" min="0" v-model="assignmentTry.max_points" :errors="errors?.[`tries.${index}.max_points`]" />
                                    <button @click="removeTry(index)">&times;</button>
                                </div>
                            </InputWithError>

                        </div>

                        <AppButton @click="addTry" size="small">Pridať</AppButton>
                    </AdminCard>
                </div>
            </div>
            <!-- <AssignmentForm @processed="updateAssignment" :errors="errors" /> -->
        </div>

        <div class="col-span-3">
            <AdminCard class="mb-5">
                <InputWithError label="Body:" :errors="errors?.points">
                    <AppInput type="number" :errors="errors?.points" v-model="assignment.points" />
                </InputWithError>
            </AdminCard>

            <AdminCard class="mb-5">
                <h3 class="mb-4 font-semibold">Publikovanie</h3>

                <InputWithError label="Dátum publikovania:">
                    <AppInput type="datetime-local" v-model="assignment.published_at" />
                </InputWithError>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" v-model="assignment.is_current" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Aktuálne zadanie</span>
                </label>
            </AdminCard>

            <AdminCard class="mb-5">
                <h3 class="mb-4 font-semibold">Tester</h3>

                <InputWithError label="Cesta k testeru:" :errors="errors.tester_path">
                    <AppInput v-model="assignment.tester_path" :errors="errors.tester_path" />
                </InputWithError>
            </AdminCard>

            <AdminCard>
                <h3 class="mb-4 font-semibold">Odovzdanie cez GitHub</h3>

                <InputWithError label="Názov branch-ky:" :errors="errors.vcs_branch">
                    <AppInput v-model="assignment.vcs_branch" :errors="errors.vcs_branch" />
                </InputWithError>
            </AdminCard>
        </div>
    </div>
</template>
