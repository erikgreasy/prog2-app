<script setup>
defineProps({
    modelValue: String,
})

defineEmits(['update:modelValue'])
</script>

<template>
    <select
        :value="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
        class="w-full border-gray-300 text-gray-900 text-sm rounded-lg">
        <slot></slot>
    </select>
</template>