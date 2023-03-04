<script setup>
import AppButton from '@/components/AppButton.vue';
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
        emit('submit')

    } catch(err) {
        console.log(err)
        console.log(err.response)
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