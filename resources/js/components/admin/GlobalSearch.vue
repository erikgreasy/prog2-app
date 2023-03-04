<script setup>
import { ref } from 'vue';
import debounce from 'debounce'
import AppInput from './forms/AppInput.vue';

const searchKey = ref('')

const results = ref([])

const loading = ref(false)

const runSearch = debounce(async () => {
    results.value = []
    loading.value = true

    if (searchKey.value.length < 2) {
        loading.value = false
        return
    }

    try {
        const res = await axios.get(`/api/fulltext-search?key=${searchKey.value}`)
        results.value = res.data
        console.log(res)
    } catch (err) {
        console.log(err)
    } finally {
        loading.value = false
    }
}, 500)

const resolveRoute = (type, id) => {
    if (type === 'assignment') {
        return {name: 'admin.assignments.edit', params: {id: id}}
    } else if (type === 'student') {
        return {name: 'admin.students.show', params: {id: id}}
    }
}

const clearSearch = () => {
    results.value = []
    searchKey.value = ''
}

</script>

<template>
    <div class="relative">
        <AppInput v-model="searchKey" @keypress="runSearch" @change="runSearch" type="text" placeholder="Hľadať..." class="w-[400px] outline-none focus:ring-0 focus:shadown-none" />

        <ul v-if="loading || results.length" class="absolute w-full bg-white p-5 shadow-xl border">
            <li v-if="loading">
                Loading...
            </li>
            <li v-else v-for="(group, index) in results" :key="index">
                <div v-if="group.results.length">
                    <strong>
                        {{ group.name }}
                    </strong>

                    <div v-for="(result, index) in group.results" :key="index">
                        <router-link @click="clearSearch" :to="resolveRoute(result.type, result.id)" class="inline-block py-2">
                            {{ result.title }}
                        </router-link>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>
