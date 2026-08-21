<template>
  <Transition name="toast">
    <div v-if="notificationStore.visible" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-2xl shadow-2xl border text-sm font-semibold text-white transition-all backdrop-blur-md"
      :class="{
        'bg-emerald-600/90 border-emerald-500': notificationStore.type === 'success',
        'bg-rose-600/90 border-rose-500': notificationStore.type === 'error',
        'bg-brand-600/90 border-brand-500': notificationStore.type === 'info',
        'bg-amber-600/90 border-amber-500': notificationStore.type === 'warning',
      }">
      <span>{{ notificationStore.message }}</span>
    </div>
  </Transition>
</template>

<script setup>
import { useNotificationStore } from '@/stores/notification';
const notificationStore = useNotificationStore();
</script>

<style scoped>
.toast-enter-active, .toast-leave-active { transition: all 0.3s ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateY(20px); }
</style>
