<script setup>
defineProps({
    items: Array,
    content: String,
})
</script>

<template>
    <li>
        <span v-html="content" class="block mb-2"></span>
        <ContentParserList v-for="(item, index) in items" :key="index" :items="item.items" :content="item.content" />
    </li>
</template>
