<template>
    <div>
        <AssignmentForm />
    </div>
</template>

<script>
import axios from 'axios'
import AssignmentForm from '../../../components/AssignmentForm.vue'

export default {
    components: {
        AssignmentForm,
    },

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
        async storeAssignment() {
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