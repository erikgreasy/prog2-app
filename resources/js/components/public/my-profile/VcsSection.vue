<script setup>
import { onMounted, ref } from 'vue'
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore()

const repos = ref(null)
const choosenRepo = ref(null)
const loading = ref(true)

const storeRepo = async () => {
    const res = await axios.post('/api/vcs/repos/store', {
        'repo': choosenRepo.value
    })

    authStore.user.github_repo = choosenRepo.value
    console.log(res)
}


onMounted(async () => {
    if(!authStore.user.github_repo) {
        const res = await axios.get('/api/vcs/repos')
        console.log(res)
        repos.value = res.data
    }
    
    loading.value = false
})
</script>

<template>
    <section>
        <h2 class="font-extrabold text-2xl">
            github
        </h2>

        <div v-if="!loading">
            <a v-if="!authStore.user.github_access_token" href="/connect-github">Connect</a>
    
            <form @submit.prevent="storeRepo" v-else-if="!authStore.user.github_repo">
                <select v-model="choosenRepo">
                    <option disabled selected>Vyberte repozitar</option>
    
                    <option v-for="repo in repos" :key="repo.id" :value="repo.name">
                        {{ repo.name }}
                    </option>
                </select>
    
                <button>Ulozit</button>
            </form>
    
            <div v-else>
                Vybrate repo: {{ authStore.user.github_repo }}
            </div>
        </div>
        <div v-else>
            Loading...
        </div>

    </section>
</template>