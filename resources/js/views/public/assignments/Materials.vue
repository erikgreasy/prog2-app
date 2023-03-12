<script setup>
import { inject } from 'vue'

const assignment = inject('assignment')
</script>

<template>
    <section class="flex justify-center">
        <ul v-if="assignment.materials?.length">
            <li v-for="material in assignment.materials" :key="material.id" v-html="material.src"></li>
        </ul>

        <div v-else>
            Žiadne materiály nie sú k dispozícii
        </div>
    </section>
</template>
