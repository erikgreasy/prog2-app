import { ref } from 'vue'
import { useRoute } from 'vue-router'

export function useAssignments() {
    const route = useRoute()
    const assignment = ref(null)

    const getAssignment = async() => {
        try {
            const res = await axios.get(`/api/assignments/${route.params.id}`)
            
            assignment.value = res.data
        } catch(err) {
            console.error(err)
            alert('Pri získavaní zadania nastala chyba')
        }
    }

    return { assignment, getAssignment }
}
