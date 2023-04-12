<script setup>
defineProps({
    block: Object,
})
</script>

<template>
    <div>
        <video controls>
            <source :src="block.data.file.url">
        </video>
    </div>
</template>
