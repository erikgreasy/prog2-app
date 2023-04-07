import { defineStore } from "pinia";

export const useProcessingAssignmentsStore = defineStore('processingAssignments', {
    state: () => {
        return {
            assignments: [],
        }
    },
    actions: {
        addAssignment(id) {
            if (this.assignments.includes(id)) {
                return
            }

            this.assignments.push(id)
        },
        
        removeAssignment(id) {
            console.log('removing ' + id)
            this.assignments = this.assignments.filter(assignmentId => assignmentId != id)
        }
    }
})
