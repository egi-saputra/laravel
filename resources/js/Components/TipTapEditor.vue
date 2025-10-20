<template>
  <div class="border rounded shadow-sm p-2 bg-white">
    <!-- Toolbar -->
    <div v-if="editor" class="flex flex-wrap gap-2 mb-2 items-center">
      <!-- Undo/Redo -->
      <button @click="editor.chain().focus().undo().run()" :disabled="!editor.can().undo()">⮪ Undo</button>
      <button @click="editor.chain().focus().redo().run()" :disabled="!editor.can().redo()">⮫ Redo</button>

      <!-- Headings -->
      <select @change="setHeading($event)" :value="currentHeading">
        <option value="">Paragraph</option>
        <option value="1">H1</option>
        <option value="2">H2</option>
        <option value="3">H3</option>
      </select>

      <!-- Bold/Italic/Underline/Strike -->
      <button @click="editor.chain().focus().toggleBold().run()" :class="{ 'font-bold': editor.isActive('bold') }">B</button>
      <button @click="editor.chain().focus().toggleItalic().run()" :class="{ 'italic': editor.isActive('italic') }">I</button>
      <button @click="editor.chain().focus().toggleStrike().run()" :class="{ 'line-through': editor.isActive('strike') }">S</button>
      <button @click="editor.chain().focus().toggleUnderline().run()" :class="{ 'underline': editor.isActive('underline') }">U</button>

      <!-- Lists -->
      <button @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'font-bold': editor.isActive('bulletList') }">• List</button>
      <button @click="editor.chain().focus().toggleOrderedList().run()" :class="{ 'font-bold': editor.isActive('orderedList') }">1. List</button>

      <!-- Link -->
      <button @click="setLink">Link</button>

      <!-- Image -->
      <button @click="setImage">Image</button>

      <!-- Table -->
      <button @click="editor.chain().focus().insertTable({ rows: 2, cols: 2, withHeaderRow: true }).run()">Table</button>
    </div>

    <!-- Editor Content -->
    <editor-content v-if="editor" :editor="editor" class="prose max-w-full min-h-[300px]" />
  </div>
</template>

<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import Link from '@tiptap/extension-link'
import Image from '@tiptap/extension-image'
import Table from '@tiptap/extension-table'
import { watch, onBeforeUnmount, ref, computed } from 'vue'

const props = defineProps({ modelValue: String })
const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
  extensions: [
    StarterKit,
    Underline,
    Link,
    Image,
    Table.configure({ resizable: true, allowTableNodeSelection: true }),
  ],
  content: props.modelValue || '',
  onUpdate({ editor }) {
    emit('update:modelValue', editor.getHTML())
  },
})

watch(() => props.modelValue, (val) => {
  if (val !== editor?.getHTML()) editor?.commands.setContent(val || '')
})

onBeforeUnmount(() => editor?.destroy())

// Toolbar helpers
const currentHeading = computed(() => {
  if (!editor) return ''
  if (editor.isActive('heading', { level: 1 })) return '1'
  if (editor.isActive('heading', { level: 2 })) return '2'
  if (editor.isActive('heading', { level: 3 })) return '3'
  return ''
})

function setHeading(event) {
  const level = event.target.value
  if (!level) editor.chain().focus().setParagraph().run()
  else editor.chain().focus().toggleHeading({ level: Number(level) }).run()
}

function setLink() {
  const url = prompt('Enter URL')
  if (url) editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run()
}

function setImage() {
  const url = prompt('Enter image URL')
  if (url) editor.chain().focus().setImage({ src: url }).run()
}
</script>

<style scoped>
button {
  padding: 0.25rem 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  cursor: pointer;
}
button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
button.font-bold { font-weight: bold; }
button.italic { font-style: italic; }
button.underline { text-decoration: underline; }
button.line-through { text-decoration: line-through; }
select {
  padding: 0.25rem;
  border-radius: 4px;
  border: 1px solid #ccc;
}
</style>
