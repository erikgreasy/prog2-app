<script setup>
import AppButton from '@/components/AppButton.vue';
import { useAuth } from '@/composables/auth';

const { openLogin }  = useAuth()
</script>

<template>
    <div class="container">
        <div class="h-[calc(100vh-68px)] flex items-center justify-center">
            <div class="lg;w-2/3">
                <h1 class="font-extrabold text-4xl mb-4 lg:text-7xl text-center lg:mb-7 dark:text-darkText">Programovanie&nbsp;2</h1>
                <div class="text-sliver text-lg text-center mb-10 lg:text-2xl">
                    Vitajte na stránkach predmetu Programovanie 2. <br>Stránka slúži na prezeranie a odovzdávanie zadaní, prehľad hodnotení.
                </div>
    
                <div class="flex gap-x-6 justify-center">
                    <AppButton href="/login" @click="openLogin" raw>Prihlásenie</AppButton>
                    <AppButton :to="{name: 'assignments.index'}" type="outline">Zadania</AppButton>
                </div>
            </div>
        </div>
    </div>
</template>
