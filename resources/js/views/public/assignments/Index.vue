<template>
    <div class="pb-20">

        <PageHeader>
            Zadania
        </PageHeader>
        <div class="container">
            <div class="lg:w-2/3 mx-auto">
                <div class="relative my-10">
                    <input ref="searchInput" type="text" class="w-full bg-[#D7D7D7] !ring-0 !ring-offset-0 !shadow-none !border-none font-semibold !outline-none rounded" v-model="search" placeholder="Vyhľadávať v zadaniach">
                    <span class="absolute top-1/2 -translate-y-1/2 right-5 text-[#8A8A8A] font-semibold">/</span>
                </div>
    
                <AssignmentCard v-for="assignment in filteredAssignments" :assignment="assignment" />

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
import PageHeader from '@/components/PageHeader.vue';
import AssignmentCard from '@/components/public/assignments/AssignmentCard.vue';

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
    assignments.value = res.data
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
