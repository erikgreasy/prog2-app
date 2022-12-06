<script setup>
defineProps({
    href: String,
    isActive: Boolean
})
</script>

<template>
    <li class="grid gap-y-5">
        <a
            :href="`#${href}`" 
            @click="$emit('set', href)" 
            :class="{'text-primary' : isActive}" 
            class="block font-semibold transition"
        >
            <slot></slot>
        </a>

        <ul v-if="$slots['subitems']" class="grid gap-y-3 pl-3 border-l border-[#dedede] text-sliver text-sm">
            <slot name="subitems"></slot>
        </ul>
    </li>
</template>
