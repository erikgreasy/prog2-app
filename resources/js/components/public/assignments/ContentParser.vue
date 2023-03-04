<script setup>
import slugify from 'slugify'
import ContentParserList from '@/components/public/assignments/ContentParserList.vue'

const makeSlug = text => {
    return slugify(text.toLowerCase())
}

const props = defineProps({
    content: Object
})

const imgIndex = id => {
    const images = props.content.blocks.filter(block => block.type === 'image')

    return images.findIndex(el => el.id === id) + 1
}
</script>

<template>
    <div class="editorjs-parser">
        <div v-for="block in content?.blocks" :key="block.id">
            <p v-if="block.type === 'paragraph'" v-html="block.data.text"></p>

            <div v-else-if="block.type === 'header'" :id="makeSlug(block.data.text)">
                <component :is="`h${block.data.level}`">
                    {{ block.data.text }}
                </component>
            </div>

            <figure v-else-if="block.type === 'image'" class="mb-10">
                <img :src="block.data.file.url" class="mx-auto" :class="{'w-full': block.data.stretched}">
                <figcaption class="mt-3 text-center">
                    <strong>Obrázok {{ imgIndex(block.id) }}<span v-if="block.data.caption">:</span></strong> {{ block.data.caption }}</figcaption>
            </figure>

            <ul v-else-if="block.type === 'list'">
                <ContentParserList v-for="(item, index) in block.data.items" :key="index" :items="item.items" :content="item.content" />
            </ul>

            <div v-else-if="block.type === 'code'">
                <pre><code v-html="block.data.code"></code></pre>
            </div>
        </div>
    </div>
</template>

<style>
.editorjs-parser p {
    margin-bottom: 2rem;
}

.editorjs-parser h2 {
    font-size: 2rem;
    margin-bottom: .8rem;
}

.editorjs-parser h3 {
    font-size: 1.5rem;
    margin-bottom: .5rem;
}

.editorjs-parser ul {
    list-style: initial;
    padding-left: 20px;
    margin-bottom: 2rem;
}

</style>
