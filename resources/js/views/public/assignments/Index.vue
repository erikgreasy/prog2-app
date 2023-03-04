<template>
    <div class="pb-20">

        <PageHeader>
            Zadania
        </PageHeader>
        <div class="container">
            <div class="w-2/3 mx-auto">
                <div class="relative my-10">
                    <input ref="searchInput" type="text" class="w-full bg-[#D7D7D7] !ring-0 !ring-offset-0 !shadow-none !border-none font-semibold !outline-none rounded" v-model="search" placeholder="Vyhľadávať v zadaniach">
                    <span class="absolute top-1/2 -translate-y-1/2 right-5 text-[#8A8A8A] font-semibold">/</span>
                </div>
    
                <article v-for="assignment in filteredAssignments" :key="assignments.id" class="border-b border-[#dadada] py-6">
                    <h3 class="text-2xl font-extrabold mb-4">
                        <router-link :to="{name: 'assignments.show', params: {slug: assignment.slug}}">
                            {{ assignment.title }}
                        </router-link>
                    </h3>
        
                    <div class="text-sliver mb-4 lg:pr-10">
                        {{ assignment.excerpt }}
                    </div>
    
                    <footer class="flex justify-between items-center">
                        <div class="text-sliver text-xs">
                            {{ assignment.deadline?.readable }}
                        </div>
                        <router-link :to="{name: 'assignments.show', params: {slug: assignment.slug}}" class="text-primary font-semibold inline-flex items-center gap-x-5 text-[15px]">
                            Zobraziť
    
                            <svg width="18" height="11" viewBox="0 0 18 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.9357 0L12.0845 0.924422L15.6957 4.84636H0V4.84641V6.15372V6.15376H15.6956L12.0845 10.0756L12.9357 11L18 5.5L12.9357 0Z" fill="#4E56A6"/>
                            </svg>
                        </router-link>
                    </footer>
                </article>

                <div v-if="!filteredAssignments.length" class="text-center py-20">
                    Žiadne výsledky
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { ref, computed, onMounted, onDeactivated, onUnmounted } from 'vue'
import PageHeader from '../../../components/PageHeader.vue';

const assignments = ref([])
const search = ref('')
const searchInput = ref(null)

const filteredAssignments = computed(() => {
    return assignments.value.filter(item => {
        return item.title.toLowerCase().includes(search.value.toLowerCase())
    })
})

const fetchAssignments = async () => {
    const res = await axios.get('/api/assignments/published')
    assignments.value = res.data.data
}

const handleKeypress = event => {
    if (event.key === '/') {
        if (searchInput.value === document.activeElement) {
            return
        }

        event.preventDefault() // prevent auto-inserting "/" into input
        searchInput.value.focus()
    }
}

onMounted(() => {
    fetchAssignments()
    window.addEventListener('keydown', handleKeypress)
})

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeypress)
})

</script>
