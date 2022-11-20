<template>
    <button v-if="button"
        :class="{
            'bg-white text-primary hover:bg-primaryDark hover:text-white' : type == 'outline',
            'bg-primary text-white hover:bg-primaryDark' : type == 'primary'
        }" 
        class=" border border-primary py-3 px-14 rounded-lg min-w-[200px] transition text-center"
    >
        <slot></slot>
    </button>
    <router-link v-else :to="to"
        :class="{
            'bg-white text-primary hover:bg-primaryDark hover:text-white' : type == 'outline',
            'bg-primary text-white hover:bg-primaryDark' : type == 'primary'
        }" 
        class=" border border-primary py-3 px-14 rounded-lg min-w-[200px] transition text-center"
    >
        <slot></slot>
    </router-link>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            default: 'primary'
        },
        button: {
            type: Boolean,
            default: false
        },
        to: [Object, String]
    }
}

</script>
