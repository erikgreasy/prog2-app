<template>
    <div>

        <PageHeader>
            Zadania
        </PageHeader>
        <div class="container">
            <div class="w-2/3 mx-auto">
                <input type="text" class="w-full bg-[#D7D7D7] rounded my-10" v-model="search">
    
                <article v-for="assignment in filteredAssignments" :key="assignments.id" class="border-b border-[#dadada] py-6">
                    <h3 class="text-2xl font-extrabold mb-4">
                        <router-link :to="{name: 'assignments.show', params: {slug: assignment.slug}}">
                            {{ assignment.title }}
                        </router-link>
                    </h3>
        
                    <div class="text-sliver mb-4">
                        {{ assignment.excerpt }}
                    </div>
    
                    <footer class="flex justify-between items-center">
                        <div class="text-sliver text-xs">
                            {{ assignment.deadline }}
                        </div>
                        <router-link :to="{name: 'assignments.show', params: {slug: assignment.slug}}" class="text-primary font-semibold inline-flex items-center gap-x-5 text-[15px]">
                            Zobraziť
    
                            <svg width="18" height="11" viewBox="0 0 18 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.9357 0L12.0845 0.924422L15.6957 4.84636H0V4.84641V6.15372V6.15376H15.6956L12.0845 10.0756L12.9357 11L18 5.5L12.9357 0Z" fill="#4E56A6"/>
                            </svg>
                        </router-link>
                    </footer>
                </article>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import PageHeader from '../../../components/PageHeader.vue';

export default {
    components: { PageHeader },
    
    data() {
        return {
            assignments: [],
            search: '',
        }
    },

    computed: {
        filteredAssignments() {
            return this.assignments.filter(item => {
                return item.title.toLowerCase().includes(this.search.toLowerCase())
            })
        }
    },

    methods: {
        async fetchAssignments() {
            const res = await axios.get('/api/assignments')
            this.assignments = res.data
        },

        runSearch() {

        }

    },

    mounted() {
        this.fetchAssignments()
    }
}
</script>
