<template>
    <div v-if="assignment">
        <PageHeader title="Upraviť zadanie">
            <AppButton 
                v-if="realSlug" 
                :to="{name: 'assignments.show', params: {slug: realSlug}}"
                size="small"
                type="outline"
            >Zobraziť</AppButton>

            <AppButton :to="{name: 'admin.assignment-tests.index', id: assignment.id}" size="small" type="outline">
                Testy
            </AppButton>

            <AppButton @click="submitForm" size="small" button>Aktualizovať</AppButton>
        </PageHeader>

        <div class="grid grid-cols-12 gap-8 items-start">
            <div class="col-span-9">
                <AssignmentForm @submit-form="alert('daco')" :errors="errors" />
            </div>
    
            <div class="col-span-3">
                <AdminCard>
                    <h3 class="mb-4 font-semibold">Publikovanie</h3>
    
                    <InputWithError label="Dátum publikovania:">
                        <AppInput type="datetime-local" v-model="assignment.published_at" />
                    </InputWithError>
                </AdminCard>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios'
import { onMounted, provide, ref, toRaw, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import AppButton from '../../../components/AppButton.vue';
import AssignmentForm from '../../../components/AssignmentForm.vue'
import useEventsBus from '@/eventBus.js'
import PageHeader from '@/components/admin/PageHeader.vue';
import { useAssignments } from '@/composables/assignments';
import ContentEditor from '@/components/admin/assignments/ContentEditor.vue';
import { useNotificationsStore } from '@/stores/notifications';
import AppInput from '@/components/admin/forms/AppInput.vue';
import InputGroup from '@/components/admin/forms/InputGroup.vue';
import InputError from '@/components/admin/forms/InputError.vue';
import InputWithError from '@/components/admin/forms/InputWithError.vue';
import AppSelect from '@/components/admin/forms/AppSelect.vue';
import InputLabel from '@/components/admin/forms/InputLabel.vue';


const route = useRoute()
const router = useRouter()

const notificationsStore = useNotificationsStore()

// const assignment = ref({})
const errors = ref([])
const realSlug = ref(null)

const { emit, bus } = useEventsBus()

const componentEmits = defineEmits(['storeAssignment'])

const submitForm = () => {
    errors.value = {}

    // emit event to get editor data
    emit('storingAssignment')



    // enjoy!
}

watch(() => bus.value.get('contentEditor'), async contentPromise => {
    const outputData = await contentPromise[0]

    assignment.value.content = outputData

    // after receiving, emit event to send request
    updateAssignment()
})




const { getAssignment, assignment } = useAssignments()

provide('assignment', assignment)

const deleteAssignment = async () => {
    const res = await axios.delete(`/api/assignments/${route.params.id}`)

    router.push({name: 'admin.assignments.index'})
}

const updateAssignment = async () => {
    console.log('try to update assignment')
    
    try {
        const res = await axios.put(`/api/assignments/${route.params.id}`, assignment.value)
        console.log(res)
        
        if(res.status === 200) {
            realSlug.value = assignment.value.slug
            notificationsStore.addNotification('Úspešne aktualizované')
            getAssignment()
        }
    } catch(err) {
        const res = err.response

        if(res.status === 422) {
            console.log(res)
            errors.value = res.data.errors

            notificationsStore.addNotification('Formulár obsahuje chyby!', 'error')

            return
        }

        alert('Pri ukladaní nasatala chyba')
        console.error(err)
    }
}


onMounted(async () => {
    await getAssignment()
    realSlug.value = assignment.value.slug
})
</script>
