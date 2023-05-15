<script setup>
const props = defineProps({
    class: String
})
</script>

<template>
    <div
        :class="class"
        class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm-light">
        <slot></slot>
    </div>
</template>
