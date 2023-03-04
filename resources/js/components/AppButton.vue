<template>
    <component
        :is="button ? 'button': 'router-link'"
        :class="{
            'bg-white text-primary hover:bg-primaryDark hover:text-white' : type == 'outline',
            'bg-primary text-white hover:bg-primaryDark' : type == 'primary',
            'py-3 px-14 min-w-[200px]' : size == 'regular',
            'py-2 px-8 text-sm min-w-[130px]' : size == 'small',
        }"
        :to="to ? to : ''"
        class=" inline-block border border-primary rounded-lg  transition text-center"
        :type="button ? role : ''"
    >
        <slot></slot>
    </component>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            default: 'primary'
        },
        size: {
            type: String,
            default: 'regular'
        },
        button: {
            type: Boolean,
            default: false
        },
        raw: {
            type: Boolean,
            default: false
        },
        to: [Object, String],
        role: {
            type: String,
            default: 'submit'
        }
    },

    computed: {
        htmlEl() {
            if(this.button) {
                return 'button'
            } else if(this.raw) {
                return 'a'
            } else {
                return 'router-link'
            }
        }
    }
}

</script>
