<script setup>
import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import { inject, onMounted, watch } from 'vue';
import useEventsBus from '@/eventBus.js';

const { bus, emit } = useEventsBus()

let editor = null


watch(() => bus.value.get('storingAssignment'), () => {
    console.log('get storing evenet in editor, lets parse the content')

    // console.log(editor)
    emit('contentEditor', editor.save())

  // destruct the parameters
    // const [sidebarCollapsedBus] = val ?? []
    // sidebarCollapsed.value = sidebarCollapsedBus
})

const assignment = inject('assignment')

onMounted(() => {

    editor = new EditorJS({
        tools: {
            header: {
                class: Header,
                config: {
                    levels: [2, 3],
                }
            }
        },
        data: assignment.value.content
    });
})

</script>

<template>
    <div class="bg-gray-50 rounded-lg px-5 py-2 border border-gray-300">
        <div id="editorjs"></div>
    </div>
</template>

<style>
#editorjs h2 {
    font-size: 30px !important;
}

#editorjs h3 {
    font-size: 24px !important;
}   

/* .ce-toolbar__actions {
    opacity: 1 !important;
} */


/* .ce-toolbar {
    display: block !important;
}

.ce-block__content {
    background: rgba(235, 235, 235, .5);
    padding: 0 10px;
    margin-bottom: 5px;
    border-radius: 6px;
} */
</style>