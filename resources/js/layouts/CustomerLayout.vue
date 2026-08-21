<template>
  <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-[#0b0f19] text-gray-900 dark:text-gray-100 font-sans">
    <Navbar />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-1">
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <aside class="lg:col-span-1 glass-card rounded-2xl p-6 h-fit border border-gray-200 dark:border-gray-800">
          <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-200 dark:border-gray-800">
            <div class="w-12 h-12 rounded-full bg-brand-500 text-white font-bold flex items-center justify-center text-xl">
              {{ authStore.user?.name?.charAt(0) }}
            </div>
            <div>
              <h3 class="font-bold text-base text-gray-900 dark:text-white">{{ authStore.user?.name }}</h3>
              <p class="text-xs text-gray-500 dark:text-gray-400">{{ authStore.user?.email }}</p>
            </div>
          </div>

          <nav class="space-y-2">
            <router-link to="/customer/dashboard" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400">
              <span>My Orders & Profile</span>
            </router-link>
            <router-link to="/cart" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
              <span>Shopping Cart</span>
            </router-link>
            <button @click="authStore.logout(); $router.push('/')" class="w-full text-left flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/30 mt-4">
              <span>Sign Out</span>
            </button>
          </nav>
        </aside>

        <main class="lg:col-span-3">
          <router-view />
        </main>
      </div>
    </div>

    <Footer />
  </div>
</template>

<script setup>
import Navbar from '@/components/common/Navbar.vue';
import Footer from '@/components/common/Footer.vue';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
</script>
