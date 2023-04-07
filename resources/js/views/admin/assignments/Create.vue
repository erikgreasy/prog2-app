<script setup>
import axios from 'axios'
import AssignmentForm from '../../../components/AssignmentForm.vue'
import AdminCard from '../../../components/AdminCard.vue'
import AppButton from '@/components/AppButton.vue'
import { useRouter } from 'vue-router'
import { ref, toRaw, watch, provide } from 'vue';
import useEventsBus from '@/eventBus.js';
import PageHeader from '@/components/admin/PageHeader.vue'
import { useNotificationsStore } from '@/stores/notifications'
import AppInput from '@/components/admin/forms/AppInput.vue';
import InputGroup from '@/components/admin/forms/InputGroup.vue';
import InputError from '@/components/admin/forms/InputError.vue';
import InputWithError from '@/components/admin/forms/InputWithError.vue';

const { emit, bus } = useEventsBus()
const router = useRouter()

const assignment = ref({
    title: '',
    deadline: '',
    content: {},
    is_current: false,
})

const errors = ref([])

const notificationsStore = useNotificationsStore()

const store = async () => {
    // console.log('try to store following assignment')

    try {
        const res = await axios.post('/api/assignments', assignment.value)
        const newAssignment = res.data

        notificationsStore.addNotification('Úspešne vytvorené')

        router.push({name: 'admin.assignments.edit', params: {id: newAssignment.id}})
    } catch(err) {
        const res = err.response

        if(res.status === 422) {
            console.log(res)
            errors.value = res.data.errors

            notificationsStore.addNotification('Formulár obsahuje chyby', 'error')

            return
        }

        alert('Pri ukladaní nasatala chyba')
        console.error(err)
    }
}

watch(() => bus.value.get('contentEditor'), async contentPromise => {
    // console.log('response from content editor!')

    const outputData = await contentPromise[0]

    assignment.value.content = outputData
    storeAssignment()
    // console.log('Promise resolved')
    // console.log(outputData)
    
    // console.log(contentPromise[0])
    // contentPromise.value.then(data => {
    // })

})

provide('assignment', assignment)

</script>

<template>
    <div>
        <PageHeader title="Upraviť zadanie">
            <AppButton @click="store" size="small" button>Uložiť</AppButton>
        </PageHeader>

        <!-- <div class="grid grid-cols-12 gap-8 items-start">
            <div class="col-span-9">
                <AssignmentForm :errors="errors" @store-assignment="storeAssignment" />
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

                    <div>
                        <label class="relative flex items-center cursor-pointer">
                            <input type="checkbox" v-model="assignment.is_current" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Aktuálne zadanie</span>
                        </label>
                        <small class="leading-none block mt-3 text-xs">Aktuálne môže byť iba jedno zadanie. Pri aktivácii sa ostatné zadania automaticky deaktivujú.</small>
                    </div>
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

                    <InputWithError label="Názov súboru:" :errors="errors.vcs_filename">
                        <AppInput v-model="assignment.vcs_filename" :errors="errors.vcs_filename" />
                    </InputWithError>
                </AdminCard>
            </div>
        </div> -->
        <AssignmentForm :errors="errors" @store-assignment="storeAssignment" />
    </div>
</template>
