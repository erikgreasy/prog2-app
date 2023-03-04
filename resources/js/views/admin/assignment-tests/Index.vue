<script setup>
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import PageHeader from '@/components/admin/PageHeader.vue';
import AppButton from '@/components/AppButton.vue';
import TestScenario from '@/components/admin/assignments/TestScenario.vue'
import { useAssignments } from '@/composables/assignments'
import NewScenario from '@/components/admin/assignments/NewScenario.vue'

const { assignment, getAssignment } = useAssignments()

const tests = ref([])

const getScenarios = async () => {
    try {
        const res = await axios.get(`/api/assignments/${assignment.value.id}/tests`)
        tests.value = res.data
    } catch (err) {
        alert('pri nacitani testov nastala chyba')
        console.log(err)
    }
}

onMounted(async () => {
    await getAssignment()

    getScenarios()
})

const scenarioCreated = async () => {
    getScenarios()
}
</script>

<template>
    <div>
        <PageHeader v-if="assignment" :title="`Testy pre '${assignment.title}'`">
            <AppButton :to="{name: 'admin.assignments.edit', params: {id: assignment.id}}" size="small" type="outline">Späť na zadanie</AppButton>
        </PageHeader>

        <div>
            <TransitionGroup name="list">
                <TestScenario v-for="test in tests" :key="test.id" :scenario="test" @deleted="getScenarios()" />
            </TransitionGroup>

            <NewScenario @created="scenarioCreated" />
        </div>
    </div>
</template>

<style scoped>
.list-move, /* apply transition to moving elements */
.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateX(30px);
}

/* ensure leaving items are taken out of layout flow so that moving
   animations can be calculated correctly. */
.list-leave-active {
  position: absolute;
}
</style>
