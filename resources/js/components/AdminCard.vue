<template>
    <div
        class="block p-4 lg:p-6 bg-white border border-gray-200 rounded-lg shadow-sm-light dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
        <slot></slot>
    </div>
</template>

<script setup>
const props = defineProps({
    class: String
})
</script>
