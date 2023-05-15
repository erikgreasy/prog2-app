<script setup>
defineProps({
    buildStatus: Boolean,
    gccErrors: Array,
    gccWarnings: Array,
})
</script>

<template>
    <div class="flex flex-col align-center w-1/2 mx-auto justify-center gap-5 text-sm my-10 lg:flex-row lg:w-auto">
        <div
            :class="{
                'bg-green-200 dark:bg-green-400': buildStatus,
                'bg-red-300 dark:bg-red-500': !buildStatus
            }"
            class="rounded-lg  py-1.5 px-5"
        >
            Build status: {{ buildStatus ? 'OK' : 'FAIL' }}
        </div>

        <div
            :class="{
                'bg-green-200 dark:bg-green-400': !gccWarnings?.length,
                'bg-orange-300 dark:bg-orange-400': gccWarnings?.length
            }"
            class="rounded-lg py-1.5 px-5"
        >
            GCC warnings: {{ gccWarnings?.length || '0' }}
        </div>

        <div
            :class="{
                'bg-green-200 dark:bg-green-400': !gccErrors?.length,
                'bg-red-300 dark:bg-red-500': gccErrors?.length
            }"
            class="rounded-lg py-1.5 px-5"
        >
            GCC errors: {{ gccErrors?.length || '0' }}
        </div>
    </div>
</template>
