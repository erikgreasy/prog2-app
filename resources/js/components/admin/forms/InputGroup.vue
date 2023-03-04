<script setup>
defineProps({
    // errors: Array
})
</script>

<template>
    <div class="mb-5 w-full">
        <slot></slot>

        <!-- <p v-if="errors" class="mt-2 text-xs text-red-600 dark:text-red-500">
            {{ errors[0] }}
        </p> -->
    </div>
</template>