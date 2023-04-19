<script setup>
import Plyr from 'plyr';
import 'plyr/dist/plyr.css'
import {onMounted} from "vue";

const props = defineProps({
    block: Object,
})

onMounted(() => {
    new Plyr(`#plyr-${props.block.id}`);
})
</script>

<template>
    <div>
        <video :id="`plyr-${block.id}`" controls>
            <source :src="block.data.file.url">
        </video>
    </div>
</template>
