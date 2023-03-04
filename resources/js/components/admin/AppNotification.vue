<script setup>
defineProps({
    type: String,
})
</script>

<template>
    <li class="border py-5 px-8 bg-white p-10 w-80 shadow-2xl flex items-center gap-x-3 rounded-lg">
        <svg v-if="type === 'success'" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path class="fill-green-400" d="M10 20C4.48372 20 0 15.5163 0 10C0 4.48372 4.48372 0 10 0C15.5163 0 20 4.48372 20 10C20 15.5163 15.5163 20 10 20ZM10 1.39535C5.25581 1.39535 1.39535 5.25581 1.39535 10C1.39535 14.7442 5.25581 18.6047 10 18.6047C14.7442 18.6047 18.6047 14.7442 18.6047 10C18.6047 5.25581 14.7442 1.39535 10 1.39535Z"/>
            <path class="fill-green-400" d="M8.67854 13.33C8.49249 13.33 8.31575 13.2556 8.18552 13.1254L5.55291 10.4928C5.28314 10.2231 5.28314 9.77655 5.55291 9.50678C5.82268 9.23702 6.26919 9.23702 6.53896 9.50678L8.67854 11.6463L13.4599 6.86492C13.7297 6.59516 14.1762 6.59516 14.446 6.86492C14.7157 7.13469 14.7157 7.5812 14.446 7.85097L9.17156 13.1254C9.04133 13.2556 8.86459 13.33 8.67854 13.33Z"/>
        </svg>

        <svg v-else-if="type === 'error'" class="w-[20px] h-[20px]" width="278" height="278" viewBox="0 0 278 278" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path class="stroke-red-400" d="M138.896 267.791C209.788 267.791 267.791 209.788 267.791 138.896C267.791 68.003 209.788 10 138.896 10C68.003 10 10 68.003 10 138.896C10 209.788 68.003 267.791 138.896 267.791Z" stroke-width="19.3343" stroke-linecap="round" stroke-linejoin="round"/>
            <path class="stroke-red-400" d="M102.418 175.371L175.373 102.417" stroke-width="19.3343" stroke-linecap="round" stroke-linejoin="round"/>
            <path class="stroke-red-400" d="M175.373 175.371L102.418 102.417" stroke-width="19.3343" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>

        <slot></slot>
    </li>
</template>
