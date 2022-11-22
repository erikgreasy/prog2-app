<template>
    <div>
        <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm-light dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
            <AssignmentForm />
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import AppButton from '../../../components/AppButton.vue'
import AssignmentForm from '../../../components/AssignmentForm.vue'

export default {
    components: {
        AppButton,
        AssignmentForm,
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