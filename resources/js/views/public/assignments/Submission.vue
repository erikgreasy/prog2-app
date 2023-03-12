<script setup>
import SubmissionSection from "@/components/public/assignments/SubmissionSection.vue";
import { inject } from "vue";

const assignment = inject('assignment')
</script>

<template>
    <SubmissionSection v-if="assignment.id" :assignment="assignment" />
</template>
