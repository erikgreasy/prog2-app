import { ref } from 'vue'

export function useAssignmentsIndex() {
    const assignments = ref([])

    const getAssignments = async() => {
        try {
            const res = await axios.get(`/api/assignments`)
            
            assignments.value = res.data
        } catch(err) {
            console.error(err)
            alert('Pri získavaní zadani nastala chyba')
        }
    }

    return { assignments, getAssignments }
}
