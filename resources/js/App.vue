<template>
    <nav>
        <ul class="flex">
            <li>
                <router-link :to="{name: 'home'}">Home</router-link>
            </li>
            <li>
                <router-link :to="{name: 'about'}">About</router-link>
            </li>
            <li>
                <router-link :to="{name: 'login'}">Login</router-link>
            </li>
            <li>
                <a href="/logout">Logout</a>
            </li>
        </ul>
    </nav>

    <router-view />
</template>

<script setup>
</script>
