<script setup>
import PageHeader from "@/components/admin/PageHeader.vue";
import AppButton from "@/components/AppButton.vue";
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import AppInput from "@/components/admin/forms/AppInput.vue";
import NewCase from "@/components/admin/assignments/NewCase.vue";

const route = useRoute()

const test = ref({})


const getTests = async () => {
    try {
        const res = await axios.get(`/api/assignments/${route.params.assignment_id}/tests/${route.params.test_id}`)
        test.value = res.data
    } catch (err) {
        console.log(err)
    }
}

const deleteCase = async caseId => {
    if (!confirm('Naozaj chcete odstranit?')) return

    try {
        const res = await axios.delete(`/api/assignments/${route.params.assignment_id}/tests/${route.params.test_id}/cases/${caseId}`)
        console.log(res)
        getTests()
    } catch (err) {
        console.log(err)
    }
}

onMounted(async () => {
    getTests()
})
</script>

<template>
    <div>
        <PageHeader :title="`Scenar`" />

        <div class="relative">
            <TransitionGroup name="list" tag="div">
                <div v-for="(testCase, caseIndex) in test.cases" :key="testCase.id" class="w-full bg-white border border-gray-300 rounded-lg mb-5 p-5">
                    <div class="grid grid-cols-2 gap-x-10 gap-y-5">
                        <div>
                            <label for="">CMDIN</label>
                            <AppInput v-model="testCase.cmd_in"/>
                        </div>
                        <div>
                            <label for="">STDIN</label>
                            <AppInput v-model="testCase.std_in"/>
                        </div>
                        <div>
                            <label for="">STDOUT</label>
                            <AppInput v-model="testCase.std_out"/>
                        </div>
                        <div>
                            <label for="">ERROUT</label>
                            <AppInput v-model="testCase.err_out"/>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <AppButton size="small" @click="deleteCase(testCase.id)" role="button" button>
                            Odstranit
                        </AppButton>
                    </div>
                </div>
            </TransitionGroup>

            <div>
                <NewCase @created="getTests()" />
            </div>
        </div>
    </div>
</template>

<style scoped>
.list-move, /* apply transition to moving elements */
.list-enter-active,
.list-leave-active {
  transition: all 0.3s ease;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateX(50%);
}

/* ensure leaving items are taken out of layout flow so that moving
   animations can be calculated correctly. */
.list-leave-active {
  position: absolute;
}
</style>
