<script setup>
import Prism from "prismjs";
import 'prismjs/themes/prism.min.css'
import { onMounted } from "vue"

defineProps({
    lang: String,
})

onMounted(() => {
    window.Prism = window.Prism || {};
    window.Prism.manual = true;
    Prism.highlightAll()
})
</script>

<template>
    <code :class="`language-${lang}`">
        <slot></slot>
    </code>
</template>