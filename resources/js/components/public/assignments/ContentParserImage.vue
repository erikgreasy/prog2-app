<script setup>
defineProps({
    block: Object,
    imgIndex: Number,
})
</script>

<template>
    <figure class="mb-10">
        <img :src="block.data.file.url" class="mx-auto" :class="{'w-full': block.data.stretched}">
        <figcaption class="mt-3 text-center">
            <strong>Obrázok {{ imgIndex }}<span v-if="block.data.caption">:</span></strong> {{ block.data.caption }}</figcaption>
    </figure>
</template>
