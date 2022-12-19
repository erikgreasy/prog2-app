<script setup>
defineEmits([
    'changeSection'
])

defineProps({
    sectionId: String,
    currentSection: String
})
</script>

<template>
    <li>
        <button @click="$emit('changeSection', sectionId)"
            :class="currentSection == sectionId ? 'text-primary' : 'text-sliver'"
            class="font-semibold text-lg inline-flex items-center gap-x-2">

            <slot></slot>
            
        </button>
    </li>
</template>
