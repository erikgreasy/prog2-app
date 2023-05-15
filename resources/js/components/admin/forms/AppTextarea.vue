<script setup>
import { computed } from 'vue';

defineProps({
    additionalClasses: {
        type: String,
        default: ''
    },
    errors: Array,
    placeholder: String,
    modelValue: String,
    disabled: {
        type: Boolean,
        default: false
    }
})

defineEmits(['update:modelValue'])
</script>

<template>
    <textarea
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :placeholder="placeholder"
        :class="[{
            'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors?.length
        }, additionalClasses]"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
        :disabled="disabled"
    >
    </textarea>
</template>
