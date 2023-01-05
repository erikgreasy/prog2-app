<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

import PageHeader from '@/components/PageHeader.vue';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore()
const currentAssignment = ref(null)

const connectGithub = () => {
    alert('connect github')
}

onMounted(async () => {
    try {
        const res = await axios.get('/api/assignments/current')
        console.log(res)
        currentAssignment.value = res.data
    } catch(err) {
        const res = err.response

        if(res.status === 500) {
            alert('Nepodarilo sa načítať aktuálne zadanie')
            console.error(err)
        }
    }
})
</script>

<template>
    <div>
        <PageHeader>
            {{ authStore.user.name }}
        </PageHeader>
        
        <div class="container">
            <section class="py-24">
                <h2 class="font-extrabold text-2xl">Aktuálne zadanie</h2>
                
                <article v-if="currentAssignment">
                    <RouterLink 
                        :to="{ name: 'assignments.show', params: {slug: currentAssignment.slug} }"
                        class="font-semibold text-xl"
                    >
                        {{ currentAssignment.title }}
                    </RouterLink>
                </article>

                <div v-else>
                    Momentálne nie je aktívne žiadne zadanie
                </div>
            </section>
    
            <hr>
    
            <section>
                <h2 class="font-extrabold text-2xl">Hodnotenie</h2>
            </section>

            <hr>

            <section>
                <h2 class="font-extrabold text-2xl">
                    github
                </h2>

                <a v-if="!authStore.user.github_access_token" href="/connect-github">Connect</a>

                <select v-else>
                    
                </select>
            </section>
        </div>
    </div>
</template>
