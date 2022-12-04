<template>
    <div class="container">
        <header>
            <h1 class="text-center font-extrabold text-4xl mt-20">{{ asssignment.title }}</h1>
        </header>

        <main class="pb-20">
            <nav class="my-20">
                <ul class="flex justify-center gap-x-8">
                    <li>
                        <button @click="showSection('content')">Zadanie</button>
                    </li>
                    <li>
                        <button @click="showSection('submission')">Odovzdanie</button>
                    </li>
                    <li>
                        <button @click="showSection('materials')">Materiály</button>
                    </li>
                </ul>
            </nav>
            <Transition name="fade" mode="out-in">
                <section v-if="currentSection == 'content'" class="grid grid-cols-4">
                    <div class="border-r border-[#D1D1D1] pr-10">
                        <aside class="sticky top-10">
                            <nav>
                                <ul>
                                    <li>
                                        <router-link to="#">Sekcia 1</router-link>
                                    </li>
                                    <li>
                                        <router-link to="#">Sekcia 1</router-link>
                                    </li>
                                    <li>
                                        <router-link to="#">Sekcia 1</router-link>
                                    </li>
                                </ul>
                            </nav>
                        </aside>
                    </div>
                    <div class="col-span-3 pl-10">
                        {{ asssignment.content }}
                    </div>
                </section>
                <section v-else-if="currentSection == 'submission'" class="grid grid-cols-4">
                    Odovzdanie
                </section>
                
                <section v-else-if="currentSection == 'materials'" class="grid grid-cols-4">
                    Materialy
                </section>
            </Transition>
        </main>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            asssignment: {},
            currentSection: 'content'
        }
    },

    methods: {
        async getAssignment() {
            const res = await axios.get(`/api/assignments/slug/${this.$route.params.slug}`)
            this.asssignment = res.data
            console.log(res)
        },

        showSection(sectionId) {
            this.currentSection = sectionId
        }
    },

    mounted() {
        this.getAssignment()
    }
}
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