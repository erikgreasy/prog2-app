<template>
    <div class="grid grid-cols-12 gap-8">
        <AdminCard class="col-span-9">
            <AssignmentForm :assignment="assignment" @store-assignment="updateAssignment" />

            <button class="text-red-600 mt-10" @click="deleteAssignment">Delete</button>
        </AdminCard>

        <AdminCard class="col-span-3">
            Bocny panel
        </AdminCard>
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
        async updateAssignment() {
            const res = await axios.put(`/api/assignments/${this.$route.params.id}`, this.assignment)

            console.log(res)
        },
        async deleteAssignment() {
            const res = await axios.delete(`/api/assignments/${this.$route.params.id}`)
            console.log(res)

            if(res.status === 200) {
                this.$router.push({name: 'admin.assignments.index'})
            }
        }
    },

    mounted() {
        this.getAssignment()
    }
}
</script>

<style>

</style>