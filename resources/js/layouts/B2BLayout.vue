<template>
  <div class="min-h-screen flex bg-gray-900 text-gray-100 font-sans">
    <!-- B2B Sidebar -->
    <aside class="w-64 bg-gray-950 border-r border-gray-800 flex flex-col justify-between hidden md:flex">
      <div>
        <div class="p-5 border-b border-gray-800 flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-blue-400 flex items-center justify-center text-white font-bold text-xl shadow-glow">
            B
          </div>
          <div>
            <h1 class="font-bold text-lg text-white">Besmart <span class="text-xs px-2 py-0.5 rounded bg-brand-500/20 text-brand-400 font-semibold border border-brand-500/30">B2B</span></h1>
            <p class="text-xs text-gray-400">Wholesale Portal</p>
          </div>
        </div>

        <nav class="p-4 space-y-1">
          <router-link to="/b2b/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors bg-brand-600 text-white shadow-glow">
            <span>Overview & Analytics</span>
          </router-link>
          <router-link to="/shop" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
            <span>Wholesale Catalog</span>
          </router-link>
        </nav>
      </div>

      <div class="p-4 border-t border-gray-800">
        <div class="bg-gray-900/80 p-3 rounded-xl border border-gray-800 mb-3">
          <p class="text-xs text-gray-400">Approved B2B Account</p>
          <p class="text-sm font-bold text-white truncate">{{ authStore.user?.name }}</p>
          <p class="text-xs text-emerald-400 mt-1 flex items-center gap-1">✓ Verified Wholesale License</p>
        </div>
        <button @click="authStore.logout(); $router.push('/')" class="w-full text-left text-xs font-semibold text-rose-400 hover:text-rose-300 py-2 px-3">
          Exit B2B Portal →
        </button>
      </div>
    </aside>

    <!-- Main Workspace -->
    <div class="flex-1 flex flex-col min-w-0">
      <header class="h-16 bg-gray-950/80 backdrop-blur-md border-b border-gray-800 px-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <router-link to="/" class="text-sm text-gray-400 hover:text-white flex items-center gap-1">← Storefront</router-link>
          <span class="text-gray-700">|</span>
          <h2 class="font-bold text-lg text-white">Business Intelligence & Wholesale Dashboard</h2>
        </div>
        <div class="flex items-center gap-3">
          <ThemeToggle />
        </div>
      </header>

      <main class="flex-1 p-6 overflow-y-auto">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import ThemeToggle from '@/components/common/ThemeToggle.vue';

const authStore = useAuthStore();
</script>
