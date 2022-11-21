<template>
    <div>

        <router-link :to="{name: 'admin.assignments.create'}">
            Nove zadanie
        </router-link>
    
        <div class="overflow-x-auto relative">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="py-3 px-6">
                            Názov zadania
                        </th>
                        <!-- <th scope="col" class="py-3 px-6">
                            Color
                        </th>
                        <th scope="col" class="py-3 px-6">
                            Category
                        </th>
                        <th scope="col" class="py-3 px-6">
                            Price
                        </th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in assignments" :key="item.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <router-link :to="{name: 'admin.assignments.edit', params: {id: item.id}}">
                                {{ item.title }}
                            </router-link>
                        </th>
                        <!-- <td class="py-4 px-6">
                            Sliver
                        </td>
                        <td class="py-4 px-6">
                            Laptop
                        </td>
                        <td class="py-4 px-6">
                            $2999
                        </td> -->
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
export default {
    data() {
        return {
            assignments: []
        }
    },

    methods: {
        async getAssignments() {
            const res = await axios.get('/api/assignments')
            this.assignments = res.data
            console.log(res)
        }
    },
    
    mounted() {
        this.getAssignments()
    }
}
</script>

<style>

</style>