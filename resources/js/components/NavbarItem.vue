<template>
    <li>
        <router-link :to="{name: routeName}" class="font-semibold text-sm text-sliver" :class="[additionalClass]">
            <slot></slot>
        </router-link>
    </li>
</template>

<script setup>
defineProps({
    routeName: {
        type: String
    },
    additionalClass: String,
})
</script>
