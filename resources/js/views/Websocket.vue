<template>
    <div>
        <button @click="submitWebsocket">SUBMIT MY WEBSOCKET EVENT</button>
    </div>
</template>

<script>
import axios from 'axios'
export default {
    methods: {
        async submitWebsocket() {
            axios.post('/websocket-handler')
        }
    }
}
</script>

<style>

</style>