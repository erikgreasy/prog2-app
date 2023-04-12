<script setup>
import slugify from 'slugify'

defineProps({
    block: Object,
})

const makeSlug = text => {
    return slugify(text.toLowerCase())
}
</script>

<template>
    <div :id="makeSlug(block.data.text)">
        <component :is="`h${block.data.level}`"
            :class="{
                'text-left': block.tunes?.alignTool?.alignment === 'left',
                'text-center': block.tunes?.alignTool?.alignment === 'center',
                'text-right': block.tunes?.alignTool?.alignment === 'right',
                'text-3xl': block.data.level == 2,
                'text-xl': block.data.level != 2,
            }"
            class="-mb-4"
            v-html="block.data.text.replace(`[fa`, `<i class='`).replace(']', `'></i>`)"
        >
        </component>
    </div>
</template>
