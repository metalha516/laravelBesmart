<template>
  <div class="min-h-screen flex bg-slate-50 text-slate-800 font-sans">
    <!-- Admin Sidebar -->
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between hidden md:flex shadow-xs">
      <div>
        <div class="p-5 border-b border-slate-100 flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-purple-600 to-brand-500 flex items-center justify-center text-white font-bold text-xl shadow-md">
            A
          </div>
          <div>
            <h1 class="font-extrabold text-lg text-slate-900">Besmart Admin</h1>
            <p class="text-xs text-purple-600 font-bold">Control Center</p>
          </div>
        </div>

        <nav class="p-4 space-y-1.5">
          <router-link to="/admin/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all bg-purple-600 text-white shadow-sm">
            <span>Control Panel & Stats</span>
          </router-link>
          <router-link to="/shop" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 hover:text-purple-600 hover:bg-purple-50 transition-all">
            <span>View Catalog</span>
          </router-link>
        </nav>
      </div>

      <div class="p-4 border-t border-slate-100">
        <button @click="authStore.logout(); $router.push('/')" class="w-full text-left text-xs font-bold text-rose-600 hover:text-rose-700 py-2.5 px-3 rounded-xl hover:bg-rose-50 transition-all cursor-pointer">
          Logout Admin →
        </button>
      </div>
    </aside>

    <!-- Main Workspace -->
    <div class="flex-1 flex flex-col min-w-0">
      <header class="h-16 bg-white/90 backdrop-blur-md border-b border-slate-200 px-6 flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-4">
          <router-link to="/" class="text-sm font-semibold text-slate-500 hover:text-purple-600 transition-colors">← Storefront</router-link>
          <span class="text-slate-300">|</span>
          <h2 class="font-extrabold text-lg text-slate-900">Platform Administration</h2>
        </div>
        <ThemeToggle />
      </header>

      <main class="flex-1 p-6 overflow-y-auto bg-slate-50">
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
