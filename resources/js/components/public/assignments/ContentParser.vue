<script setup>
import slugify from 'slugify'
import { onMounted } from 'vue'
import ContentParserList from '@/components/public/assignments/ContentParserList.vue'
import ContentParserBlock from '@/components/public/assignments/ContentParserBlock.vue'
import PrismCode from '@/components/PrismCode.vue';

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

onMounted(() => {
    setTimeout(function () {
        window.MathJax.typeset()
    }, 300) // this for some reason make things work
})
</script>

<template>
    <div class="editorjs-parser">
        <ContentParserBlock v-for="block in content?.blocks" :key="block.id">
            <p 
                v-if="block.type === 'paragraph'" v-html="block.data.text" 
                :class="{
                    'text-left': block.tunes.alignTool.alignment === 'left',
                    'text-center': block.tunes.alignTool.alignment === 'center',
                    'text-right': block.tunes.alignTool.alignment === 'right',
                }"
            ></p>

            <div v-else-if="block.type === 'header'" :id="makeSlug(block.data.text)">
                <component :is="`h${block.data.level}`"
                    :class="{
                        'text-left': block.tunes?.alignTool?.alignment === 'left',
                        'text-center': block.tunes?.alignTool?.alignment === 'center',
                        'text-right': block.tunes?.alignTool?.alignment === 'right',
                    }"

                    v-html="block.data.text.replace(`[fa`, `<i class='`).replace(']', `'></i>`)"
                >
                </component>
            </div>

            <figure v-else-if="block.type === 'image'" class="mb-10">
                <img :src="block.data.file.url" class="mx-auto" :class="{'w-full': block.data.stretched}">
                <figcaption class="mt-3 text-center">
                    <strong>Obrázok {{ imgIndex(block.id) }}<span v-if="block.data.caption">:</span></strong> {{ block.data.caption }}</figcaption>
            </figure>

            <component v-else-if="block.type === 'list'" :is="block.data.style === 'ordered' ? 'ol' : 'ul'">
                <ContentParserList v-for="(item, index) in block.data.items" :key="index" :items="item.items" :content="item.content" />
            </component>

            <div v-else-if="block.type === 'code'">
                <PrismCode>
                    {{ block.data.code }}
                </PrismCode>
                <!-- <pre><code v-html="block.data.code"></code></pre> -->
            </div>

            <div v-else-if="block.type === 'raw'">
                <div v-html="block.data.html"></div>
            </div>

            <div v-else-if="block.type === 'attaches'" class="grid grid-cols-[27px,auto] items-center gap-x-3 mb-5">
                <svg width="27" height="31" viewBox="0 0 27 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.6042 0.765152H0V30.4992H26.0415V9.27484L17.6042 0.765152ZM24.0761 10.0959V10.1809H16.7058V2.74742H16.7902L24.0761 10.0959ZM1.96539 28.5169V2.74742H14.7404V12.1632H24.0761V28.5169H1.96539Z" fill="black"/>
                </svg>

                <a :href="block.data.file.url" target="_blank" v-text="block.data.title || block.data.file.url"></a>
            </div>

            <div v-else-if="block.type === 'video'">
                <video controls>
                    <source :src="block.data.file.url">
                </video>
            </div>

            <hr v-else-if="block.type === 'hr'" class="my-16">
        </ContentParserBlock>
    </div>
</template>

<style>
/* .editorjs-parser p {
    margin-bottom: 2rem;
} */

.editorjs-parser h2 {
    font-size: 2rem;
    margin-bottom: -1rem;
}

.editorjs-parser h3 {
    font-size: 1.5rem;
    margin-bottom: -1rem;
}

/* .editorjs-parser ul,
.editorjs-parser ol {
    list-style: initial;
    padding-left: 20px;
    margin-bottom: 2rem;
}

.editorjs-parser ul {
    list-style: initial;
}

.editorjs-parser ol {
    list-style: decimal;
} */

</style>
