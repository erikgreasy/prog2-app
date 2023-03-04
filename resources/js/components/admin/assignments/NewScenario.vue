<script setup>
import { ref } from "vue";
import AppButton from "@/components/AppButton.vue";
import AppInput from "../forms/AppInput.vue";
import InputLabel from "../forms/InputLabel.vue";
import { useRoute } from "vue-router";
import InputWithError from "@/components/admin/forms/InputWithError.vue";

const route = useRoute()

const emit = defineEmits([
    'created',
])

const creating = ref(false)

const scenario = ref({})

const errors = ref({})

const createScenario = async () => {
    try {
        const res = await axios.post(`/api/assignments/${route.params.id}/tests`, scenario.value)

        console.log(res)
        scenario.value = {}
        emit('created')
        creating.value = false
        // router.push({name: 'admin.assignment-tests.index', params: {id: route.params.id}})
    } catch (err) {
        console.log(err)
        const res = err.response

        console.log(res)
        errors.value = res.data.errors
    }
}
</script>

<template>
    <div>
        <AdminCard v-if="creating">
            <form @submit.prevent="createScenario">
                <div>
                    <InputLabel>Názov:</InputLabel>
                    <InputWithError :errors="errors.title">
                        <AppInput v-model="scenario.title" />
                    </InputWithError>
                </div>
        
                <div>
                    <InputLabel>Počet bodov:</InputLabel>
                    <InputWithError :errors="errors.points">
                        <AppInput v-model="scenario.points" />
                    </InputWithError>
                </div>
        
                <AppButton button>Uložiť</AppButton>
                <button @click="creating = false" type="butotn">Close</button>
            </form>
        </AdminCard>
    
        <div class="text-center" v-if="!creating">
            <AppButton @click="creating = true" size="small" button>
                Nový scenár
            </AppButton>
        </div>
    </div>
</template>
