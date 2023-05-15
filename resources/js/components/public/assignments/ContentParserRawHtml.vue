<script setup>
defineProps({
    block: Object,
})
</script>

<template>
    <div v-html="block.data.html" ></div>
</template>
