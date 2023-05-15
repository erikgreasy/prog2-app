<script setup>
defineProps({
    head: Array
})
</script>

<template>
    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
        <tr>
            <th v-for="(item, index) in head" :key="index" scope="col" class="py-3 px-6 font-semibold">
                {{ item }}
            </th>
        </tr>
    </thead>
</template>
