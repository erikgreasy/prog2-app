<script setup>
defineProps({
    routeName: {
        type: String
    },
    additionalClass: String,
})

defineEmits(['clicked'])
</script>

<template>
    <li>
        <router-link
            @click="$emit('clicked')"
            :to="{name: routeName}"
            class="py-5 block font-semibold text-sm text-sliver dark:text-darkText"
            :class="[additionalClass]"
        >
            <slot></slot>
        </router-link>
    </li>
</template>
