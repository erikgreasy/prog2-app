<script setup>
import ContentParser from '@/components/public/assignments/ContentParser.vue';
import { inject } from 'vue'

const assignment = inject('assignment')
</script>

<template>
    <section class="w-1/2 mx-auto">
        <ContentParser :content="assignment.materials" />
    </section>
</template>
