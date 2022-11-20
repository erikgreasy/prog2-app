<template>
    <div class="h-screen flex items-center justify-center">
        <div>
            <h1 class="font-extrabold text-7xl text-center">Programovanie 2</h1>
            <div>
                Vitajte na stránkach predmetu Programovanie 2. Stránka slúži na prezeranie a odovzdávanie zadaní, prehľad hodnotení.
            </div>

            <div class="flex gap-x-6 justify-center">
                <AppButton :to="{name: 'login'}">Prihlásenie</AppButton>
                <AppButton :to="{name: 'assignments.index'}" type="outline">Zadania</AppButton>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppButton from '../components/AppButton.vue';

</script>

<style>

</style>