<script setup>
const props = defineProps({
    assignment: Object
})

const submitAssignment = async () => {
    const res = await axios.post(`/api/assignments/${props.assignment.id}/submit`)
    console.log(res)
}
</script>

<template>
    <section class="grid grid-cols-4">
        <button @click="submitAssignment">Odovzdať</button>
    </section>
</template>
