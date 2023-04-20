<script setup>
import PrismCode from "@/components/PrismCode.vue";

defineProps({
    submission: Object,
})
</script>

<template>
    <div>
        <a :href="`/api/submissions/${submission.id}/file`" target="_blank">Odovzdaný súbor</a>

        <PrismCode lang="cpp" :showInvisibles="false" :useLineNumbers="true">
            {{ submission.file_content }}
        </PrismCode>
    </div>
</template>
