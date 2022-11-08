<template>
    <div>
        <form @submit.prevent="storeAssignment">
            <input type="text" v-model="assignment.title">

            <input type="datetime-local" v-model="assignment.deadline">

            <button>Submit</button>
        </form>
    </div>
</template>

<script>
import axios from 'axios'
export default {
    data() {
        return {
            assignment: {
                title: '',
                deadline: ''
            }
        }
    },

    methods: {
        async storeAssignment() {
            try {
                const res = await axios.post('/api/assignments', this.assignment)
                const newAssignment = res.data

                this.$router.push({name: 'admin.assignments.edit', params: {id: newAssignment.id}})
            } catch(err) {
                console.log(err)
                console.log(err.response)
            }
        }
    },
}
</script>

<style>

</style>