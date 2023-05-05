<script setup>
import { ref, computed } from 'vue'
import slugify from 'slugify'

import ContentNavItem from '../../../components/public/assignments/ContentNavItem.vue';
import ContentNavSubItem from '../../../components/public/assignments/ContentNavSubItem.vue';
import ContentParser from '../../../components/public/assignments/ContentParser.vue';

const props = defineProps({
    assignment: Object
})

const activeHref = ref('sekcia-1-a')

const activateHref = (hrefId) => {
    activeHref.value = hrefId;
}

const mainSections = computed(() => {
    const headingBlocks = props.assignment?.content?.blocks?.filter(block => {
        return block.type === 'header'
    })

    const output = []

    headingBlocks?.forEach(block => {
        if (block.data.level === 2) {
            output.push({
                title: block.data.text,
                slug: slugify(block.data.text.toLowerCase()),
                subItems: []
            })
        } else {
            const lastEl = output[output.length - 1]

            lastEl.subItems.push({
                title: block.data.text,
                slug: slugify(block.data.text.toLowerCase()),
            })
        }
    })

    return output
})
</script>

<template>
    <section class="lg:grid lg:grid-cols-4">
        <div class="mb-14 border-r border-[#D1D1D1] pr-10 dark:border-gray-600">
            <aside class="sticky top-10">
                <nav>
                    <ul class="grid gap-y-5">
                        <ContentNavItem v-for="section in mainSections" @set="activateHref" :href="section.slug"
                            :is-active="(activeHref === section.slug)">
                            {{ section.title }}

                            <template #subitems v-if="section.subItems.length">
                                <ContentNavSubItem v-for="subsection in section.subItems" @set="activateHref"
                                    :href="subsection.slug" :is-active="(activeHref === subsection.slug)">
                                    {{ subsection.title }}
                                </ContentNavSubItem>
                            </template>
                        </ContentNavItem>
                    </ul>
                </nav>
            </aside>
        </div>
        <div class="lg:col-span-3 lg:pl-10">
            <ContentParser :content="assignment.content" />
        </div>
    </section>
</template>
