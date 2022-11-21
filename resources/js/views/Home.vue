<template>
    <div class="container">
        <div class="h-[calc(100vh-68px)] flex items-center justify-center">
            <div class="w-2/3">
                <h1 class="font-extrabold text-7xl text-center mb-7">Programovanie 2</h1>
                <div class="text-sliver text-2xl text-center mb-10">
                    Vitajte na stránkach predmetu Programovanie 2. Stránka slúži na prezeranie a odovzdávanie zadaní, prehľad hodnotení.
                </div>
    
                <div class="flex gap-x-6 justify-center">
                    <AppButton :to="{name: 'login'}">Prihlásenie</AppButton>
                    <AppButton :to="{name: 'assignments.index'}" type="outline">Zadania</AppButton>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppButton from '../components/AppButton.vue';

</script>

<style>

</style>