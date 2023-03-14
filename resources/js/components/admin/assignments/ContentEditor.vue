<script setup>
import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import ImageTool from '@editorjs/image';
import { inject, onMounted, watch } from 'vue';
import useEventsBus from '@/eventBus.js';
import List from '@editorjs/list';
import NestedList from '@editorjs/nested-list';
import InlineCode from '@editorjs/inline-code'
import CodeTool from '@editorjs/code'
import RawTool from '@editorjs/raw'

const { bus, emit } = useEventsBus()

let editor = null

const props = defineProps({
    name: String,
})

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
        holder: `editor-${props.name}`,
        tools: {
            list: {
                class: NestedList,
                inlineToolbar: true,
                config: {
                    defaultStyle: 'unordered'
                }
            },
            header: {
                class: Header,
                config: {
                    levels: [2, 3],
                }
            },
            code: CodeTool,
            inlineCode: {
                class: InlineCode,
                shortcut: 'CMD+SHIFT+M',
            },
            image: {
                class: ImageTool,
                config: {
                    uploader: {
                        async uploadByFile(file) {
                            const formData = new FormData()
                            formData.append('file', file)

                            const res = await axios.post('/api/upload-file', formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })

                            return res.data
                        }
                    }
                    // endpoints: {
                    //     byFile: '/api/upload-file', // Your backend file uploader endpoint
                    //     byUrl: '/storage', // Your endpoint that provides uploading by Url
                    // }
                }
            },
            raw: RawTool,
        },
        data: assignment.value.content
    });
})

</script>

<template>
    <div class="bg-gray-50 rounded-lg px-5 py-2 border border-gray-300">
        <div :id="`editor-${name}`" class="editorjs-editor"></div>
    </div>
</template>

<style>
.editorjs-editor h2 {
    font-size: 30px !important;
}

.editorjs-editor h3 {
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