<script setup>
import PrismCode from '@/components/PrismCode.vue'

defineProps({
    block: Object,
})
</script>

<template>
    <div>
        <PrismCode>
            {{ block.data.code }}
        </PrismCode>
    </div>
</template>
