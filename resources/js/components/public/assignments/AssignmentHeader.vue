<script setup>
defineProps({
    assignment: Object,
})
</script>

<template>
    <header>
        <h1 class="text-center font-extrabold text-4xl mb-5 mt-10 lg:mt-20">{{ assignment.title }}</h1>

        <div class="flex-col items-center lg:flex-row flex justify-center gap-x-4 lg:gap-x-8">
            <div class="mb-4"><span class="font-semibold">Deadline:</span> <span class="text-primary font-semibold">{{ assignment.deadline?.readable }}</span></div>
            <div class="text-xs lg:text-base"><span class="font-semibold">Max. počet bodov:</span> <span class="text-primary font-semibold">{{ assignment.points?.readable }}</span></div>
            <div class="text-xs lg:text-base"><span class="font-semibold">Zverejnené:</span> <span class="text-sliver">{{ assignment.published_at?.readable }}</span></div>
        </div>
    </header>
</template>