<script setup>
defineProps({
    materials: Array,
})
</script>

<template>
    <section class="flex justify-center">
        <ul v-if="materials.length">
            <li v-for="material in materials" :key="material.id" v-html="material.src"></li>
        </ul>

        <div v-else>
            Žiadne materiály nie sú k dispozícii
        </div>
    </section>
</template>