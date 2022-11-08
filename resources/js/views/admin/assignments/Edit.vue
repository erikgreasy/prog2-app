<template>
    <div>
        <h2 class="text-3xl">{{ assignment.title }}</h2>

        <button @click="deleteAssignment" class="bg-red-400">DELETE</button>
    </div>
</template>

<script>
import axios from 'axios'
export default {
    data() {
        return {
            assignment: {}
        }
    },

    methods: {
        async getAssignment() {
            const res = await axios.get(`/api/assignments/${this.$route.params.id}`)
            this.assignment = res.data
        },
        async deleteAssignment() {
            const res = await axios.delete(`/api/assignments/${this.$route.params.id}`)

            this.$router.push({name: 'admin.assignments.index'})
        }
    },

    mounted() {
        this.getAssignment()
    }
}
</script>

<style>

</style>