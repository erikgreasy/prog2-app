<script setup>
defineProps({
    block: Object,
})
</script>

<template>
    <div class="grid grid-cols-[27px,auto] items-center gap-x-3 mb-5">
        <svg width="27" height="31" viewBox="0 0 27 31" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.6042 0.765152H0V30.4992H26.0415V9.27484L17.6042 0.765152ZM24.0761 10.0959V10.1809H16.7058V2.74742H16.7902L24.0761 10.0959ZM1.96539 28.5169V2.74742H14.7404V12.1632H24.0761V28.5169H1.96539Z" fill="black"/>
        </svg>

        <a :href="block.data.file.url" target="_blank" v-text="block.data.title || block.data.file.url"></a>
    </div>
</template>
