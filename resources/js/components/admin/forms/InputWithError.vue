<script setup>
import InputGroup from '@/components/admin/forms/InputGroup.vue'
import AppInput from '@/components/admin/forms/AppInput.vue'
import InputError from '@/components/admin/forms/InputError.vue'
import InputLabel from './InputLabel.vue';

defineProps({
    label: String,
    errors: Array,
})
</script>

<template>
    <InputGroup>
        <InputLabel v-if="label">{{ label }}</InputLabel>

        <slot></slot>

        <InputError :errors="errors" />
    </InputGroup>    
</template>
