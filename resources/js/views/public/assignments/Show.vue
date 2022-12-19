<template>
    <div class="container">
        <header>
            <h1 class="text-center font-extrabold text-4xl mt-20">{{ asssignment.title }}</h1>
        </header>

        <main class="pb-20">
            <nav class="my-20">
                <ul class="flex justify-center gap-x-8">
                    <li>
                        <button @click="showSection('content')" 
                            :class="currentSection == 'content' ? 'text-primary' : 'text-sliver'" 
                            class="font-semibold text-lg inline-flex items-center gap-x-2"
                        >
                            <svg width="25" height="27" viewBox="0 0 25 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.03564 13.784H15.0668M8.03564 18.264H12.4351M10.0445 6.84H14.0623C16.0712 6.84 16.0712 5.72 16.0712 4.6C16.0712 2.36 15.0668 2.36 14.0623 2.36H10.0445C9.04009 2.36 8.03564 2.36 8.03564 4.6C8.03564 6.84 9.04009 6.84 10.0445 6.84Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M16.071 4.62239C19.4158 4.82399 21.0932 6.20159 21.0932 11.32V18.04C21.0932 22.52 20.0887 24.76 15.0665 24.76H9.03985C4.01763 24.76 3.01318 22.52 3.01318 18.04V11.32C3.01318 6.21279 4.69061 4.82399 8.03541 4.62239" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            Zadanie
                        </button>
                    </li>
                    <li>
                        <button @click="showSection('submission')" 
                            :class="currentSection == 'submission' ? 'text-primary' : 'text-sliver'" 
                            class="font-semibold text-lg inline-flex items-center gap-x-2"
                        >
                            <svg width="25" height="27" viewBox="0 0 25 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.6665 9.64H15.2221M6.68428 18.6H8.69317M11.2043 18.6H15.2221" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M22.7554 15.8336V18.1632C22.7554 22.0944 21.8614 23.08 18.2957 23.08H7.12624C3.56046 23.08 2.6665 22.0944 2.6665 18.1632V8.95679C2.6665 5.02559 3.56046 4.03999 7.12624 4.03999H15.2221M20.7465 10.76V4.03999L22.7554 6.27999M20.7465 4.03999L18.7376 6.27999" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            Odovzdanie
                        </button>
                    </li>
                    <li>
                        <button @click="showSection('materials')" 
                            :class="currentSection == 'materials' ? 'text-primary' : 'text-sliver'" 
                            class="font-semibold text-lg inline-flex items-center gap-x-2"
                        >
                            <svg width="25" height="27" viewBox="0 0 25 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_114_206)">
                                <path d="M22.4944 7.5712L19.1094 22.7248C18.9919 23.2728 18.7116 23.7603 18.314 24.1081C17.9164 24.4558 17.425 24.6433 16.9197 24.64H3.7213C2.20459 24.64 1.11979 22.9824 1.57179 21.3584L5.8005 6.216C5.9418 5.70247 6.22657 5.25273 6.61325 4.93238C6.99993 4.61203 7.46829 4.43783 7.95001 4.4352H20.3047C21.2589 4.4352 22.0524 5.0848 22.3839 5.9808C22.5747 6.4624 22.6149 7.0112 22.4944 7.5712Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"/>
                                <path d="M16.5381 24.64H21.3394C22.6351 24.64 23.6496 23.4192 23.5592 21.9744L22.5648 6.72001M10.19 7.14561L11.2346 2.30721M16.9198 7.15681L17.864 2.29601M8.20122 13.44H16.2368M7.19678 17.92H15.2323" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_114_206">
                                <rect width="24.1067" height="26.88" fill="white" transform="translate(0.466797)"/>
                                </clipPath>
                                </defs>
                            </svg>

                            Materiály
                        </button>
                    </li>
                </ul>
            </nav>
            <Transition name="fade" mode="out-in">
                <section v-if="currentSection == 'content'" class="grid grid-cols-4">
                    <div class="border-r border-[#D1D1D1] pr-10">
                        <aside class="sticky top-10">
                            <nav>
                                <ul class="grid gap-y-5">
                                    <ContentNavItem v-for="section in mainSections" 
                                        @set="activateHref" 
                                        :href="section.slug" 
                                        :is-active="(activeHref === section.slug)"
                                    >
                                        {{ section.title }}
    
                                        <template #subitems v-if="section.subItems.length">
                                            <ContentNavSubItem
                                                v-for="subsection in section.subItems" 
                                                @set="activateHref" 
                                                :href="subsection.slug" 
                                                :is-active="(activeHref === subsection.slug)"
                                            >
                                                {{ subsection.title }}
                                            </ContentNavSubItem>
                                        </template>
                                    </ContentNavItem>
                                </ul>
                            </nav>
                        </aside>
                    </div>
                    <div class="col-span-3 pl-10">
                        <ContentParser :content="asssignment.content" />
                    </div>
                </section>
                <section v-else-if="currentSection == 'submission'" class="grid grid-cols-4">
                    <button @click="submitAssignment">Odovzdať</button>
                </section>
                
                <section v-else-if="currentSection == 'materials'" class="grid grid-cols-4">
                    Materialy
                </section>
            </Transition>
        </main>
    </div>
</template>

<script setup>
import axios from 'axios';
import ContentNavItem from '../../../components/public/assignments/ContentNavItem.vue';
import ContentNavSubItem from '../../../components/public/assignments/ContentNavSubItem.vue';
import ContentParser from '../../../components/public/assignments/ContentParser.vue';
import slugify from 'slugify'
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const asssignment = ref({})
const currentSection = ref('content')
const activeHref = ref('sekcia-1-a')
  
const getAssignment = async () => {
    const res = await axios.get(`/api/assignments/slug/${route.params.slug}`);
    asssignment.value = res.data;
}

const showSection = (sectionId) => {
    currentSection.value = sectionId
}

const activateHref = (hrefId) => {
    activeHref.value = hrefId;
}
        
const submitAssignment = async () => {
    const res = await axios.post(`/api/assignments/${asssignment.value.id}/submit`)
    console.log(res)
}

onMounted(() => {
    getAssignment()
})


const mainSections = computed(() => {
    const headingBlocks = asssignment.value.content?.blocks.filter(block => {
        return block.type === 'header'
    })

    const output = []

    headingBlocks?.forEach(block => {
        if(block.data.level === 2) {
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
<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>