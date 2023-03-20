<script setup>
defineProps({
    type: {
        type: String,
        default: 'text'
    },
    additionalClasses: {
        type: String,
        default: ''
    },
    errors: Array,
    placeholder: String,
    modelValue: String,
    disabled: {
        type: Boolean,
        default: false,
    }
})

defineEmits(['update:modelValue'])
</script>

<template>
    <input 
        :type="type"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :placeholder="placeholder"
        :class="[{
            'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500': errors?.length,
            'bg-gray-300' : disabled
        }, additionalClasses]"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
        :disabled="disabled"
    >
</template>
