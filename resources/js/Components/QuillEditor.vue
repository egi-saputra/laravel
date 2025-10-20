<template>
  <quill-editor v-model="content" :options="editorOptions" />
</template>

<script setup>
import { QuillEditor } from 'vue3-quill' // import hanya komponen
import 'quill/dist/quill.snow.css' // tema
import { ref, watch } from 'vue'

const props = defineProps({ modelValue: String })
const emit = defineEmits(['update:modelValue'])

const content = ref(props.modelValue)

const editorOptions = {
  modules: {
    toolbar: [
      ['bold', 'italic', 'underline', 'strike'],
      [{ header: [1, 2, 3, false] }],
      [{ list: 'ordered' }, { list: 'bullet' }],
      ['link', 'image'],
      ['clean']
    ]
  },
  theme: 'snow'
}

watch(content, (val) => emit('update:modelValue', val))
watch(() => props.modelValue, (val) => {
  if (val !== content.value) content.value = val
})
</script>
