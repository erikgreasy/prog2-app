<script setup>
defineProps({
    assignments: Array,
    student: Object,
})
</script>

<template>
    <div class="mb-10 border">
        <header class="bg-primary text-white py-3 px-4">
            <h3 class="text-xl">
                {{ student.full_name }} (#{{student.id}})
            </h3>
            <div class="text-sm">
                <div>
                    {{ student.email }}
                </div>
                <div>
                    {{ student.username }}
                </div>
            </div>
        </header>

        <div class="bg-gray-200 py-5 px-5 flex gap-x-10">
            <div>
                Odovzdané zadania: {{ new Set(student.submissions.map(submission => submission.assignment_id)).size }}/{{ assignments.length }}
            </div>
            <div>
                Aktuálny stav bodov: {{ student.total_points }}
            </div>
        </div>

        <div>
            <div v-for="assignment in assignments" :key="assignment.id" class="px-5 mb-10">
                <div class="py-10">
                    <h4 class="font-bold text-primary text-lg mb-2">
                        {{ assignment.title }}
                    </h4>

                    <div v-if="student.submissions.find(submission => submission.is_final)" class="flex gap-x-10">
                        <div>
                            Získané body: {{ student.submissions.find(submission => submission.is_final)?.points }}
                        </div>
                        <div>
                            Celkom odovzdaní: {{ student.submissions.filter(submission => submission.assignment_id === assignment.id).length }}
                        </div>
                    </div>
                </div>

                <div>
                    <div v-if="student.submissions.filter(submission => submission.assignment_id === assignment.id).length">
                        <div v-for="submission in student.submissions.filter(submission => submission.assignment_id === assignment.id)" class="px-5 mb-5">
                            <div class="text-primary">
                                <router-link :to="{name: 'admin.users.submissions.show', params: {userId: student.id, submissionId: submission.id}}">
                                    Odovzdanie: {{ submission.created_at.readable }}
                                    (
                                    <span v-if="submission.status !== 'failed'">pokus {{ submission.try }}</span>
                                    <span v-else class="text-red-600">neúspešný pokus</span>
                                    )
                                </router-link>
                            </div>

                            <table class="mx-5 my-3">
                                <tr>
                                    <td>Max. počet bodov</td>
                                    <td class="px-3">{{ assignment.points.raw }}</td>
                                </tr>
                                <tr>
                                    <td>Stav</td>
                                    <td class="px-3">
                                        <span v-if="submission.status == 'failed'" class="bg-red-300 px-2 py-0.5 rounded">Neúspešné</span>
                                        <span v-else-if="submission.status == 'completed'" class="bg-green-300 px-2 py-0.5 rounded">Vyhodnotené</span>
                                        <span v-else class="bg-orange-300 px-2 py-0.5 rounded">Spracúva sa</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Získané body pred penalizáciou</td>
                                    <td class="px-3">{{ submission.points_before_penalisation }}</td>
                                </tr>
                                <tr>
                                    <td>Získané body po penalizácii</td>
                                    <td class="px-3">{{ submission.points }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div v-else class="px-5 mb-5 text-sliver">
                        Zatiaľ žiadne odovzdania
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
