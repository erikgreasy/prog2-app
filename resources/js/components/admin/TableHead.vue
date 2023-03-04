<script setup>
defineProps({
    head: Array
})
</script>

<template>
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th v-for="(item, index) in head" :key="index" scope="col" class="py-3 px-6 font-semibold">
                {{ item }}
            </th>
        </tr>
    </thead>
</template>
