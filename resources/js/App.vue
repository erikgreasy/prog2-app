<template>
    <nav>
        <ul class="flex">
            <li>
                <router-link :to="{name: 'home'}">Home</router-link>
            </li>
            <li>
                <router-link :to="{name: 'about'}">About</router-link>
            </li>
        </ul>
    </nav>

    <router-view />
</template>

<script setup>
</script>
