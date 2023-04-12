<script setup>
import PageHeader from '@/components/PageHeader.vue';
import AppSelect from '@/components/admin/forms/AppSelect.vue'
import AppButton from '@/components/AppButton.vue'
import { useAuthStore } from '@/stores/auth';
import { ref, onMounted, computed } from 'vue'


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

const currentStep = computed(() => {
    if (!authStore.user.github_access_token || !authStore.user.vcs_username) {
        return 1
    }

    if (!authStore.user.github_repo) {
        return 2
    }

    return 3
})
</script>

<template>
    <div>
        <PageHeader>
            Prepojenie GitHub-u
        </PageHeader>

        <div class="container py-20">
            <section class="text-center">
                <h3 class="text-2xl font-bold mb-3">
                    Vaše prepojenie je 
                    <span v-if="currentStep == 3" class="text-green-600">kompletné</span>
                    <span v-else class="text-red-600">nekompletné</span>
                </h3>

                <div v-if="currentStep != 3" class="text-sliver text-lg">
                    Posupujte podľa krokov nižšie
                </div>

                <div v-else class="text-sliver text-lg">
                    Teraz môžete odovzdávať zadania z prepojeného repozitáru.
                </div>
            </section>

            

            <section class="mt-10">
                <div v-if="currentStep == 3" class="text-xl mt-5 mx-auto flex items-center justify-center gap-x-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>

                    <a :href="`https://github.com/${authStore.user.vcs_username}/${authStore.user.github_repo}`" target="_blank">
                        {{ authStore.user.vcs_username }}/{{ authStore.user.github_repo }}
                    </a>
                </div>

                <ol v-else class="list-decimal w-[400px] mx-auto text-xl">
                    <li :class="{'text-black': currentStep == 1}" class="mb-5 text-sliver">
                        <component 
                            :is="currentStep == 1 ? 'a' : 'href'" 
                            href="/connect-github"
                            :class="{'underline': currentStep == 1}"
                            class="text-xl flex items-center gap-x-4"
                        >
                            Autorizujte aplikáciu Programovanie 2

                            <svg v-if="currentStep == 1" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </component>
                    </li>

                    <li :class="{'text-black': currentStep == 2}" class="mb-5 text-sliver">
                        <div href="/connect-github" class="text-xl inline-flex items-center gap-x-4">
                            Vyberte repozitár
                        </div>

                        <div v-if="currentStep == 2" class="mt-3">
                            <span v-if="loading">Loading...</span>

                            <div v-else>
                                <AppSelect v-model="choosenRepo" class="mb-3">
                                    <option v-for="repo in repos">
                                        {{ repo.name }}
                                    </option>
                                </AppSelect>
    
                                <div class="text-center">
                                    <AppButton @click="storeRepo">
                                        Uložiť
                                    </AppButton>
                                </div>
                            </div>
                        </div>
                    </li>
                </ol>
            </section>
        </div>
    </div>
</template>