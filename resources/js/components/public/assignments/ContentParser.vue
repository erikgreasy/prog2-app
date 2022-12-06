<script setup>
import slugify from 'slugify'

const makeSlug = text => {
    return slugify(text.toLowerCase())
}

defineProps({
    content: Object
})
</script>

<template>
    <div id="editorjs">
        <div v-for="block in content?.blocks" :ket="block.id">
            <p v-if="block.type === 'paragraph'" v-html="block.data.text" class="mb-60"></p>

            <div v-else-if="block.type === 'header'" :id="makeSlug(block.data.text)" class="mb-80">
                <component :is="`h${block.data.level}`">
                    {{ block.data.text }}
                </component>
            </div>
        </div>
    </div>
</template>
