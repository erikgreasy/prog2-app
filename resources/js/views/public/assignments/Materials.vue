<script setup>
import ContentParser from '@/components/public/assignments/ContentParser.vue';
import { inject } from 'vue'

const assignment = inject('assignment')
</script>

<template>
    <section class="lg:w-fit lg:mx-auto">
        <ContentParser :content="assignment.materials" />
    </section>
</template>
