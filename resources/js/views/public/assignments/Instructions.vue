<script setup>
import ContentParser from '@/components/public/assignments/ContentParser.vue';
import { inject } from 'vue'

const assignment = inject('assignment')
</script>

<template>
    <section>
        <ContentParser :content="assignment.submission_instructions" />
    </section>
</template>
