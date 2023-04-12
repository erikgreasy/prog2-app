<script setup>
import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import ImageTool from '@editorjs/image';
import { onMounted } from 'vue';
import NestedList from '@editorjs/nested-list';
import InlineCode from '@editorjs/inline-code'
import CodeTool from '@editorjs/code'
import RawTool from '@editorjs/raw'
import AttachesTool from '@editorjs/attaches'
import VideoTool from '@vietlongn/editorjs-video';
import AlignmentTuneTool from 'editorjs-text-alignment-blocktune'
import Paragraph from '@editorjs/paragraph'
import HR from '@/editorjs/hr'

const emit = defineEmits(['processed'])

let editor = null

const props = defineProps({
    name: String,
    content: Object,
})

const save = async () => {
    const content = await editor.save()

    return content
}

defineExpose({save: save})

onMounted(() => {

    editor = new EditorJS({
        holder: `editor-${props.name}`,
        defaultBlock: 'paragraph',
        tools: {
            paragraph: {
                class: Paragraph,
                inlineToolbar: true,
                tunes: ['alignTool'],
            },
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
                    defaultLevel: 2,
                },
                tunes: ['alignTool'],
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
                }
            },
            raw: RawTool,
            attaches: {
                class: AttachesTool,
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
                }
            },
            video: {
                class: VideoTool,
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
                }
            },
            alignTool: {
                class:AlignmentTuneTool,
            },

            hr: HR,
        },
        data: props.content
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
</style>