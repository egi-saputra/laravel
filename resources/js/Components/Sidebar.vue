<template>
  <aside class="w-64 bg-white dark:bg-gray-800 h-full shadow-md">
    <nav class="p-4 space-y-2">
      <a href="/guru/dashboard" class="block text-gray-700 dark:text-gray-200 hover:text-indigo-600">
        Dashboard
      </a>
      <a href="/guru/materi" class="block text-gray-700 dark:text-gray-200 hover:text-indigo-600">
        Materi
      </a>
      <a href="/guru/tugas" class="block text-gray-700 dark:text-gray-200 hover:text-indigo-600">
        Tugas
      </a>
    </nav>
  </aside>
</template>

<script setup>
// Tidak perlu script kalau hanya tampilan statis
</script>

<style scoped>
aside {
  min-height: 100vh;
}
</style>
