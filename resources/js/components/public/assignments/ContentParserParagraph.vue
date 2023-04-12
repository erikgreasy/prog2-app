<script setup>
defineProps({
    block: Object,
})
</script>

<template>
    <p 
        v-html="block.data.text" 
        :class="{
            'text-left': block.tunes.alignTool.alignment === 'left',
            'text-center': block.tunes.alignTool.alignment === 'center',
            'text-right': block.tunes.alignTool.alignment === 'right',
        }"
    ></p>
</template>
