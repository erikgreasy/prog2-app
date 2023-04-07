<script setup>
import { onMounted, ref } from "vue";
import PrismCode from '@/components/PrismCode.vue'
import PageHeader from "@/components/admin/PageHeader.vue";
import AppButton from "@/components/AppButton.vue";

const errorLog = ref('')

const getErrorLog = async () => {
    const res = await axios.get('/api/error-log')
    console.log(res)
    errorLog.value = res.data
}

onMounted(() => {
    getErrorLog()
})

const deleteLog = async () => {
    if (!confirm('Naozaj chcete odstrániť obsah error logu?')) {
        return
    }

    const res = await axios.delete('/api/error-log')
    getErrorLog()
}
</script>

<template>
    <div>
        <PageHeader title="Error log">
            <AppButton @click="deleteLog" size="small" type="danger">
                Vyprázdniť error log
            </AppButton>
        </PageHeader>
        <div class="text-sm">
            <PrismCode :useLineNumbers="true">
                {{errorLog}}
            </PrismCode>
        </div>
    </div>
</template>
