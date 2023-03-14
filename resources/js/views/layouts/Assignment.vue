<script setup>
import Public from './Public.vue';
import AssignmentHeader from '@/components/public/assignments/AssignmentHeader.vue';
import { onMounted, provide, ref } from 'vue';
import { useRoute } from 'vue-router';


const assignment = ref({})

const route = useRoute()
const error = ref(null)

onMounted(() => {
    axios.get(`/api/assignments/slug/${route.params.slug}`)
        .then(res => {
            console.log(res)
            assignment.value = res.data
        })
        .catch(err => {
            error.value = err
        })

})

provide('assignment', assignment)
</script>

<template>
    <Public>
        <div>
            <div class="container">
                <AssignmentHeader :assignment="assignment" />

                <nav class="my-20">
                    <ul class="flex justify-center gap-x-8">
                        <li>
                            <router-link :to="{name: 'assignments.show'}" 
                                :class="route.name === 'assignments.show' ? 'text-primary' : 'text-sliver'" 
                                class="font-semibold text-lg inline-flex items-center gap-x-2"
                            >
                                <svg width="25" height="27" viewBox="0 0 25 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.03564 13.784H15.0668M8.03564 18.264H12.4351M10.0445 6.84H14.0623C16.0712 6.84 16.0712 5.72 16.0712 4.6C16.0712 2.36 15.0668 2.36 14.0623 2.36H10.0445C9.04009 2.36 8.03564 2.36 8.03564 4.6C8.03564 6.84 9.04009 6.84 10.0445 6.84Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M16.071 4.62239C19.4158 4.82399 21.0932 6.20159 21.0932 11.32V18.04C21.0932 22.52 20.0887 24.76 15.0665 24.76H9.03985C4.01763 24.76 3.01318 22.52 3.01318 18.04V11.32C3.01318 6.21279 4.69061 4.82399 8.03541 4.62239" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
        
                                Zadanie
                            </router-link>
                        </li>
                        <li>
                            <router-link :to="{name: 'assignments.show.submission'}"
                                :class="route.name === 'assignments.show.submission' ? 'text-primary' : 'text-sliver'" 
                                class="font-semibold text-lg inline-flex items-center gap-x-2"
                            >
                                <svg width="25" height="27" viewBox="0 0 25 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.6665 9.64H15.2221M6.68428 18.6H8.69317M11.2043 18.6H15.2221" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M22.7554 15.8336V18.1632C22.7554 22.0944 21.8614 23.08 18.2957 23.08H7.12624C3.56046 23.08 2.6665 22.0944 2.6665 18.1632V8.95679C2.6665 5.02559 3.56046 4.03999 7.12624 4.03999H15.2221M20.7465 10.76V4.03999L22.7554 6.27999M20.7465 4.03999L18.7376 6.27999" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
        
                                Odovzdanie
                            </router-link>
                        </li>
                        <li>
                            <router-link :to="{name: 'assignments.show.instructions'}"
                                :class="route.name === 'assignments.show.instructions' ? 'text-primary' : 'text-sliver'" 
                                class="font-semibold text-lg inline-flex items-center gap-x-2"
                            >
                                <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.57489 20.8027C3.82412 20.8027 3.12258 20.5662 2.61797 20.1273C1.97797 19.5758 1.67028 18.7429 1.78104 17.8425L2.23643 14.1959C2.32258 13.5093 2.77797 12.5977 3.3072 12.1024L13.4118 2.32184C15.9349 -0.120511 18.5687 -0.188042 21.2395 2.11925C23.9103 4.42654 23.9841 6.83512 21.4611 9.27747L11.3564 19.0581C10.8395 19.5645 9.87951 20.0373 9.12874 20.1499L5.16566 20.7688C4.95643 20.7801 4.77181 20.8027 4.57489 20.8027ZM17.3626 2.10799C16.4149 2.10799 15.5903 2.64824 14.7534 3.4586L4.64874 13.2506C4.40258 13.4869 4.11951 14.0497 4.07028 14.3761L3.61489 18.0226C3.56566 18.394 3.66412 18.6979 3.88566 18.8892C4.1072 19.0806 4.43951 19.1481 4.84566 19.0918L8.80874 18.4729C9.16566 18.4166 9.75643 18.1239 10.0026 17.8875L20.1072 8.10694C21.6334 6.62127 22.1872 5.24816 19.9595 3.3348C18.9749 2.46816 18.1257 2.10799 17.3626 2.10799Z" fill="currentColor"/>
                                    <path d="M19.0984 11.1567C19.0738 11.1567 19.0369 11.1567 19.0123 11.1567C15.1723 10.8078 12.083 8.14043 11.4923 4.65136C11.4184 4.1899 11.763 3.76221 12.2676 3.68342C12.7723 3.61589 13.24 3.93103 13.3261 4.39249C13.7938 7.11622 16.2061 9.20966 19.2092 9.47978C19.7138 9.5248 20.083 9.94124 20.0338 10.4026C19.9723 10.8303 19.5661 11.1567 19.0984 11.1567Z" fill="currentColor"/>
                                    <path d="M23.6026 24.4383H1.44871C0.944096 24.4383 0.525635 24.0556 0.525635 23.5941C0.525635 23.1327 0.944096 22.75 1.44871 22.75H23.6026C24.1072 22.75 24.5256 23.1327 24.5256 23.5941C24.5256 24.0556 24.1072 24.4383 23.6026 24.4383Z" fill="currentColor"/>
                                </svg>

                                Pokyny
                            </router-link>
                        </li>
                        <li>
                            <router-link :to="{name: 'assignments.show.materials'}"
                                :class="route.name === 'assignments.show.materials' ? 'text-primary' : 'text-sliver'" 
                                class="font-semibold text-lg inline-flex items-center gap-x-2"
                            >
                                <svg width="25" height="27" viewBox="0 0 25 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_114_206)">
                                    <path d="M22.4944 7.5712L19.1094 22.7248C18.9919 23.2728 18.7116 23.7603 18.314 24.1081C17.9164 24.4558 17.425 24.6433 16.9197 24.64H3.7213C2.20459 24.64 1.11979 22.9824 1.57179 21.3584L5.8005 6.216C5.9418 5.70247 6.22657 5.25273 6.61325 4.93238C6.99993 4.61203 7.46829 4.43783 7.95001 4.4352H20.3047C21.2589 4.4352 22.0524 5.0848 22.3839 5.9808C22.5747 6.4624 22.6149 7.0112 22.4944 7.5712Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"/>
                                    <path d="M16.5381 24.64H21.3394C22.6351 24.64 23.6496 23.4192 23.5592 21.9744L22.5648 6.72001M10.19 7.14561L11.2346 2.30721M16.9198 7.15681L17.864 2.29601M8.20122 13.44H16.2368M7.19678 17.92H15.2323" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_114_206">
                                    <rect width="24.1067" height="26.88" fill="white" transform="translate(0.466797)"/>
                                    </clipPath>
                                    </defs>
                                </svg>
        
                                Materiály
                            </router-link>
                        </li>
                    </ul>
                </nav>
        
                <main class="pb-20">
                    <Transition name="fade" mode="out-in">
                        <slot></slot>
                    </Transition>
                </main>
            </div>
        </div>
    </Public>
</template>