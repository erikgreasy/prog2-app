<script setup>
import Prism from "prismjs";
import 'prismjs/themes/prism.min.css'
import 'prismjs/components/prism-c.min.js'
import 'prismjs/components/prism-cpp.min.js'
import 'prismjs/plugins/line-numbers/prism-line-numbers.js'
import 'prismjs/plugins/line-numbers/prism-line-numbers.css'
import 'prismjs/plugins/toolbar/prism-toolbar.js'
import 'prismjs/plugins/toolbar/prism-toolbar.css'
import 'prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard.js'
import 'prismjs/plugins/show-invisibles/prism-show-invisibles.js'
import './../../css/prism-show-invisibles.css'
import { onMounted } from "vue"

defineProps({
    lang: {
        type: String,
        default: 'txt',
    },
    useLineNumbers: {
        type: Boolean,
        default: false,
    },
    showInvisibles: {
        type: Boolean,
        default: true,
    }
})

onMounted(() => {
    window.Prism = window.Prism || {};
    window.Prism.manual = true;
    Prism.highlightAll()
})
</script>

<template>
    <div 
        :class="{
            'line-numbers': useLineNumbers,
            'show-invisibles': showInvisibles,
        }"
        data-prismjs-copy-timeout="2000"
    >
        <pre data-prismjs-copy="Copy"><code :class="`language-${lang}`" class="block w-full px-4 max-h-[500px]"><slot></slot></code></pre>
    </div>
</template>

<style>
.copy-to-clipboard-button {
    border-radius: 0 !important;
    background: #4e56a6 !important;
    box-shadow: none !important;
    color: white !important;
    padding: .5rem 1.5rem !important;
}
</style>
