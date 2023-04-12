<script setup>
import { onMounted } from 'vue'
import ContentParserList from '@/components/public/assignments/ContentParserList.vue'
import ContentParserBlock from '@/components/public/assignments/ContentParserBlock.vue'
import ContentParserImage from '@/components/public/assignments/ContentParserImage.vue'
import ContentParserCode from '@/components/public/assignments/ContentParserCode.vue'
import ContentParserRawHtml from '@/components/public/assignments/ContentParserRawHtml.vue'
import ContentParserParagraph from '@/components/public/assignments/ContentParserParagraph.vue'
import ContentParserVideo from '@/components/public/assignments/ContentParserVideo.vue'
import ContentParserHeader from '@/components/public/assignments/ContentParserHeader.vue'
import ContentParserHr from '@/components/public/assignments/ContentParserHr.vue'
import ContentParserAttachment from '@/components/public/assignments/ContentParserAttachment.vue'
import PrismCode from '@/components/PrismCode.vue';

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

            <!-- paragraph -->
            <ContentParserParagraph v-if="block.type === 'paragraph'" :block="block" />

            <!-- header -->
            <ContentParserHeader v-else-if="block.type === 'header'" :block="block" />

            <!-- image -->
            <ContentParserImage v-else-if="block.type === 'image'" :block="block" :imgIndex="imgIndex(block.id)" />

            <!-- list -->
            <component
                v-else-if="block.type === 'list'" 
                :is="block.data.style === 'ordered' ? 'ol' : 'ul'" 
                :class="{
                    'list-decimal': block.data.style == 'ordered',
                    'list-disc': block.data.style != 'ordered'
                }"
                class="pl-5">
                <ContentParserList v-for="(item, index) in block.data.items" :key="index" :items="item.items" :content="item.content" />
            </component>

            <!-- code -->
            <ContentParserCode v-else-if="block.type === 'code'" :block="block" />

            <!-- raw html -->
            <ContentParserRawHtml v-else-if="block.type === 'raw'" :block="block" />

            <!-- attachment -->
            <ContentParserAttachment v-else-if="block.type === 'attaches'" :block="block" />

            <!-- video -->
            <ContentParserVideo v-else-if="block.type === 'video'" :block="block" />

            <!-- hr -->
            <ContentParserHr v-else-if="block.type === 'hr'" />
        </ContentParserBlock>
    </div>
</template>
