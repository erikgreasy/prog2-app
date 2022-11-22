<template>
    <div class="container">
        <header>
            <h1 class="text-center font-extrabold text-4xl mt-20">{{ asssignment.title }}</h1>
        </header>

        <main class="pb-20">
            <nav class="my-20">
                <ul class="flex justify-center gap-x-8">
                    <li>
                        <router-link to="#">Zadanie</router-link>
                    </li>
                    <li>
                        <router-link to="#">Odovzdanie</router-link>
                    </li>
                    <li>
                        <router-link to="#">Materiály</router-link>
                    </li>
                </ul>
            </nav>

            <section class="grid grid-cols-4">
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
        </main>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            asssignment: {}
        }
    },

    methods: {
        async getAssignment() {
            const res = await axios.get(`/api/assignments/${this.$route.params.id}`)
            this.asssignment = res.data
            console.log(res)
        }
    },

    mounted() {
        this.getAssignment()
    }
}
</script>
