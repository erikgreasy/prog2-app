<template>
    <div>
        <AdminCard>
            <AssignmentForm :assignment="assignment" @store-assignment="storeAssignment" />
        </AdminCard>
    </div>
</template>

<script>
import axios from 'axios'
import AppButton from '../../../components/AppButton.vue'
import AssignmentForm from '../../../components/AssignmentForm.vue'
import AdminCard from '../../../components/AdminCard.vue'

export default {
    components: {
        AppButton,
        AssignmentForm,
        AdminCard,
    },
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