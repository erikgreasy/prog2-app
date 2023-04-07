<script setup>
import AppButton from '@/components/AppButton.vue';
import { useProcessingAssignmentsStore } from '@/stores/processingAssignments';
import { ref } from 'vue'

const file = ref(null)

const emit = defineEmits([
    'submit',
    'closeModal'
])

const props = defineProps({
    assignmentId: Number,
    isOpen: {
        type: Boolean,
        default: false,
    }
})

const errors = ref({})

const processingAssignmentsStore = useProcessingAssignmentsStore()

const onFileChange = (e) => {
    // console.log(e.target.files)
    file.value = e.target.files[0]
}

const submitManually = async () => {
    const formData = new FormData()
    formData.append('file', file.value)

    console.log(formData)

    try {
        const res = await axios.post(`/api/assignments/${props.assignmentId}/manual-submit`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        console.log(res)

        processingAssignmentsStore.addAssignment(props.assignmentId)

        emit('submit')

    } catch(err) {
        processingAssignmentsStore.removeAssignment(props.assignmentId)

        const res = err.response

        if (!res) {
            alert('Pri odvzdávaní nastala chyba')
            return
        }

        if (res.status === 400) {
            alert(res.data.message)
            return
        }

        if (res.status === 422) {
            errors.value = res.data.errors
            alert('Niektoré z polí nie je správne vyplnené. Skontrolujte odovzdávací formulár')

            return
        }

        alert('Pri odvzdávaní nastala chyba')
    }
}

const closeModal = event => {
    if (event.target != document.querySelector('.modal-bg')) {
        return
    }

    emit('closeModal')
    // console.log(event.target)
}
</script>

<template>
    <Transition>
        <div v-if="isOpen" @click="closeModal($event)" class="modal-bg fixed z-10 top-0 left-0 w-full h-full bg-black bg-opacity-60 flex items-center justify-center">
            <div class="p-10 bg-white w-4/5 rounded-lg">
                <form @submit.prevent="submitManually" enctype="multipart/form-data">
                    <div class="mb-5">
                        <input @change="onFileChange" type="file">
                        <div v-if="errors.file?.length" class="text-sm text-red-600 mt-2">{{ errors.file[0] }}</div>
                    </div>

                    <AppButton button>Odovzdať</AppButton>
                </form>
            </div>
        </div>    
    </Transition>
</template>


<style>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.2s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}</style>