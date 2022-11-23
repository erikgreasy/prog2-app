<template>
    <li>
        <router-link :to="{name: routeName}" class="font-semibold text-sm text-sliver">
            <slot></slot>
        </router-link>
    </li>
</template>

<script setup>
import { defineProps } from 'vue'

defineProps({
    routeName: {
        type: String
    }
})
</script>
