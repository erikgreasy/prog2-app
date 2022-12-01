<template>
    <div>
        <AdminCard>
            <div class="overflow-x-auto relative shadow">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6">
                                Názov zadania
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in users" :key="item.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <router-link :to="{name: 'admin.users.edit', params: {id: item.id}}">
                                    {{ item.name }}
                                </router-link>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </AdminCard>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            users: []
        }
    },

    methods: {
        async getUsers() {
            const res = await axios.get('/api/users')
            console.log(res)
            this.users = res.data
        }
    },
    
    mounted() {
        this.getUsers()
    }
}
</script>

<style>

</style>