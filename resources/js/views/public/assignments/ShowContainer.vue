<script>
import { h, ref } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'
import PageNotFound from '@/views/PageNotFound.vue'

export default {
    setup(props, ctx) {
        const route = useRoute()
        const assignment = ref({})
        const error = ref(null)

        axios.get(`/api/assignments/slug/${route.params.slug}`)
            .then(res => {
                console.log(res)
                assignment.value = res.data
            })
            .catch(err => {
                error.value = err
            })

        return () => error.value ? h(PageNotFound) : ctx.slots.default({
            assignment: assignment.value,
            error: error.value,
        })
    }
}
</script>
