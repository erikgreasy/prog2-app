<script setup>
defineProps({
    errors: Array,
})
</script>

<template>
    <p v-if="errors" class="my-2 text-xs text-red-600 dark:text-red-500">
        {{ errors[0] }}
    </p>    
</template>
