<template>
  <div class="min-h-screen bg-white">
    <router-view />
    <Toast />
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import Toast from '@/components/common/Toast.vue';

const authStore = useAuthStore();

onMounted(() => {
  authStore.checkAuth();
});
</script>
