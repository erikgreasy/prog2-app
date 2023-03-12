<script setup>
defineProps({
    assignment: Object,
})
</script>

<template>
    <header>
        <h1 class="text-center font-extrabold text-4xl mt-20 mb-5">{{ assignment.title }}</h1>

        <div class="flex justify-center gap-x-8">
            <div><span class="font-semibold">Deadline:</span> <span class="text-primary font-semibold">{{ assignment.deadline?.readable }}</span></div>
            <div><span class="font-semibold">Max. počet bodov:</span> <span class="text-primary font-semibold">{{ assignment.points?.readable }}</span></div>
            <div><span class="font-semibold">Zverejnené:</span> <span class="text-sliver">{{ assignment.published_at?.readable }}</span></div>
        </div>
    </header>
</template>