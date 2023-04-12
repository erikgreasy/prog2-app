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

<template>
    <component
        :is="button ? 'button': 'router-link'"
        :class="{
            'bg-white text-primary border-primary hover:bg-primaryDark hover:text-white' : type == 'outline',
            'bg-primary text-white border-primary hover:bg-primaryDark' : type == 'primary',
            'bg-red-700 text-white border-red-700 hover:bg-red-800' : type == 'danger',
            'py-3 text-sm px-10 min-w-[160px] lg:px-14 lg:min-w-[200px] lg:text-base' : size == 'regular',
            'py-2 px-8 text-sm min-w-[130px] lg:text-base' : size == 'small',
            'py-2 px-8 text-xs min-w-[130px]' : size == 'xs',
        }"
        :to="to ? to : ''"
        class=" inline-block border rounded-lg transition text-center"
        :type="button ? role : ''"
    >
        <slot></slot>
    </component>
</template>
