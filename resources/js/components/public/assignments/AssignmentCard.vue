<script setup>
import AssignmentCardFooter from './AssignmentCardFooter.vue';

defineProps({
    assignment: Object,
    minimalLayout: {
        type: Boolean,
        default: false,
    },
})
</script>

<template>
    <article :class="{'border-b': !minimalLayout}" class="border-[#dadada] py-6">
        <div class="flex items-center mb-4">
            <h3 class="text-2xl font-extrabold">
                <router-link :to="{name: 'assignments.show', params: {slug: assignment.slug}}">
                    {{ assignment.title }}
                </router-link>
            </h3>

            <span v-if="!minimalLayout && assignment.is_current" class="ml-5 py-1 px-3 rounded-lg text-white bg-primary text-sm">
                Aktuálne zadanie
            </span>
        </div>

        <div class="text-sliver mb-4 lg:pr-10">
            {{ assignment.excerpt }}
        </div>

        <AssignmentCardFooter :assignment="assignment" />
    </article>
</template>