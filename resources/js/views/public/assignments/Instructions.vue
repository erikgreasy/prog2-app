<script setup>
import { inject } from 'vue'

const assignment = inject('assignment')
</script>

<template>
    <section class="flex justify-center">
        {{ assignment.submission_instructions }}
    </section>
</template>
