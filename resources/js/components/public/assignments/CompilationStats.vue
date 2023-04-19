<script setup>
defineProps({
    buildStatus: Boolean,
    gccErrors: Array,
    gccWarnings: Array,
})
</script>

<template>
    <div class="flex justify-center gap-x-5 text-sm my-10">
        <div
            :class="{
                'bg-green-200': buildStatus,
                'bg-red-300': !buildStatus
            }"
            class="rounded-lg  py-1.5 px-5"
        >
            Build status: {{ buildStatus ? 'OK' : 'FAIL' }}
        </div>

        <div
            :class="{
                'bg-green-200': !gccWarnings?.length,
                'bg-orange-300': gccWarnings?.length
            }"
            class="rounded-lg py-1.5 px-5"
        >
            GCC warnings: {{ gccWarnings?.length || '0' }}
        </div>

        <div
            :class="{
                'bg-green-200': !gccErrors?.length,
                'bg-red-300': gccErrors?.length
            }"
            class="rounded-lg py-1.5 px-5"
        >
            GCC errors: {{ gccErrors?.length || '0' }}
        </div>
    </div>
</template>
