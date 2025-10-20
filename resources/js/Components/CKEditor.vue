<template>
  <div>
    <ckeditor
      :editor="editor"
      :model-value="modelValue"
      @update:model-value="updateValue"
    />
  </div>
</template>

<script setup>
import { CKEditor } from '@ckeditor/ckeditor5-vue'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'

const props = defineProps({
  modelValue: String
})
const emit = defineEmits(['update:modelValue'])

const editor = ClassicEditor

function updateValue(value) {
  emit('update:modelValue', value)
}
</script>
