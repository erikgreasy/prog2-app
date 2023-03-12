<template>
    <div>
        <ContentSection :assignment="assignment" />
    </div>
</template>

<script setup>
import { inject, ref } from 'vue'
import ContentSection from '@/components/public/assignments/ContentSection.vue';
import SubmissionSection from '@/components/public/assignments/SubmissionSection.vue';
import AssignmentHeader from '@/components/public/assignments/AssignmentHeader.vue';
import ShowContainer from './ShowContainer.vue';

const assignment = inject('assignment')

// const currentSection = ref('content')

// const showSection = (sectionId) => {
//     currentSection.value = sectionId
// }
</script>
<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>