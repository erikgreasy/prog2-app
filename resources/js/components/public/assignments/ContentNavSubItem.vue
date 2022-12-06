<script setup>
defineProps({
    href: String,
    isActive: Boolean
})
</script>

<template>
    <li class="relative">
        <!-- colored bullet -->
        <Transition
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            enter-active-class="duration-100"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
            leave-active-class="duration-100"
        >
            <span 
                v-if="isActive" 
                class="h-2 w-2 inline-block bg-primary absolute left-0 -translate-x-1/2 rounded-full top-1/2 -translate-y-1/2 -ml-3"
            ></span>
        </Transition>

        <a
            :href="`#${href}`" 
            @click="$emit('set', href)" 
            :class="{'text-primary font-semibold' : isActive}"
            class="transition duration-600"
        >
            <slot></slot>
        </a>
    </li>
</template>
