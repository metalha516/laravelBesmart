<template>
  <button @click="themeStore.toggleTheme()" class="p-2 rounded-xl text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" title="Toggle Light/Dark Theme">
    <span v-if="themeStore.isDark" class="text-xl">🌙</span>
    <span v-else class="text-xl">☀️</span>
  </button>
</template>

<script setup>
import { useThemeStore } from '@/stores/theme';
const themeStore = useThemeStore();
</script>
