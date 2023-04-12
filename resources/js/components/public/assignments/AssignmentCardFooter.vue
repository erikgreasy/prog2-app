<script setup>
import ShowMoreLink from '../ShowMoreLink.vue';

defineProps({
    assignment: Object,
})
</script>

<template>
    <footer class="flex justify-between items-center">
        <div class="text-sliver text-xs">
            {{ assignment.deadline?.readable }}
        </div>

        <ShowMoreLink :to="{name: 'assignments.show', params: {slug: assignment.slug}}" >
            Zobraziť
        </ShowMoreLink>
        <!-- <router-link :to="{name: 'assignments.show', params: {slug: assignment.slug}}" class="text-primary font-semibold inline-flex items-center gap-x-5 text-[15px]">
            Zobraziť

            <svg width="18" height="11" viewBox="0 0 18 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.9357 0L12.0845 0.924422L15.6957 4.84636H0V4.84641V6.15372V6.15376H15.6956L12.0845 10.0756L12.9357 11L18 5.5L12.9357 0Z" fill="#4E56A6"/>
            </svg>
        </router-link> -->
    </footer>
</template>