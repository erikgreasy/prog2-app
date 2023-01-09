<script setup>
defineProps({
    title: String
})
</script>

<template>
    <div class="flex justify-between items-center mb-5">
        <h1 class="font-semibold text-2xl">{{ title }}</h1>

        <div class="flex items-center gap-x-5">
            <slot></slot>
        </div>
    </div>
</template>
