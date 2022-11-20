<template>
    <div class="container">
        <article v-for="assignment in assignments" :key="assignments.id">
            <h3 class="text-2xl font-extrabold">
                <router-link :to="{name: 'assignments.show', params: {id: assignment.id}}">
                    {{ assignment.title }}
                </router-link>
            </h3>
        </article>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    components: {},
    
    data() {
        return {
            assignments: []
        }
    },

    methods: {
        async fetchAssignments() {
            const res = await axios.get('/api/assignments')
            this.assignments = res.data
        }
    },

    mounted() {
        this.fetchAssignments()
    }
}
</script>
