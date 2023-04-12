import { ref } from 'vue'

export function useAssignmentsPublished() {
    const assignments = ref([])

    const getAssignments = async() => {
        try {
            const res = await axios.get(`/api/assignments/published`)
            
            assignments.value = res.data
        } catch(err) {
            console.error(err)
            alert('Pri získavaní zadani nastala chyba')
        }
    }

    return { assignments, getAssignments }
}
