<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const props = defineProps({
    routeName: String
})

const isActive = computed(() => {
    return route.matched.some(({ name }) => name === props.routeName)
})
</script>

<template>
    <li>
        <router-link :to="{ name: routeName }"
            :class="{ 'bg-gray-100': isActive }"
            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 group">

            <slot name="icon">
                <svg aria-hidden="true"
                    :class="{ '!text-gray-900' : isActive }"
                    class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-gray-900"
                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
            </slot>
            
            <span class="ml-3">
                <slot></slot>
            </span>
        </router-link>
    </li>
</template>
