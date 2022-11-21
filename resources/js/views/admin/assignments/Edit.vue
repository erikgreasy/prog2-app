<template>
    <div>
        <form @submit.prevent="updateAssignment">
            <input type="text" v-model="assignment.title">

            <textarea cols="30" rows="10" v-model="assignment.excerpt"></textarea>

            <button>Aktualizovať</button>
        </form>

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
        },
        async updateAssignment() {
            const res = await axios.put(`/api/assignments/${this.$route.params.id}`, this.assignment)

            console.log(res)
        }
    },

    mounted() {
        this.getAssignment()
    }
}
</script>

<style>

</style>