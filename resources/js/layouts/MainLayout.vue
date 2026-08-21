<template>
  <div class="min-h-screen flex flex-col bg-gray-50 text-gray-900 font-sans">
    <!-- Main Storefront Navbar -->
    <Navbar @open-wheel="showWheelModal = true" />

    <!-- Main View Content -->
    <main class="flex-grow">
      <router-view />
    </main>

    <!-- Footer -->
    <Footer />

    <!-- Mobile Bottom Tab Bar (Daraz/Shopee-style) -->
    <nav class="mobile-bottom-nav">
      <router-link to="/" class="mobile-bottom-nav__item" :class="{ 'mobile-bottom-nav__item--active': $route.name === 'home' }">
        <span class="mobile-bottom-nav__icon">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
        </span>
        <span>Home</span>
      </router-link>
      <router-link to="/shop" class="mobile-bottom-nav__item" :class="{ 'mobile-bottom-nav__item--active': $route.name === 'shop' }">
        <span class="mobile-bottom-nav__icon">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
        </span>
        <span>Catalog</span>
      </router-link>
      <router-link to="/cart" class="mobile-bottom-nav__item" :class="{ 'mobile-bottom-nav__item--active': $route.name === 'cart' }">
        <span class="mobile-bottom-nav__icon relative inline-block">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
          <span v-if="cartStore.itemCount > 0" class="mobile-bottom-nav__badge">{{ cartStore.itemCount }}</span>
        </span>
        <span>Cart</span>
      </router-link>
      <router-link
        :to="authStore.isAuthenticated ? '/customer/dashboard' : '/login'"
        class="mobile-bottom-nav__item"
        :class="{ 'mobile-bottom-nav__item--active': $route.name === 'customer-dashboard' || $route.name === 'login' }"
      >
        <span class="mobile-bottom-nav__icon">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
        </span>
        <span>Account</span>
      </router-link>
    </nav>

    <!-- Floating Besmart AI Shopping Assistant Widget -->
    <BesmartAIChatbot />

    <!-- Gamified Spinning Discount Wheel Modal -->
    <DiscountWheel v-if="showWheelModal" @close="showWheelModal = false" />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useCartStore } from '@/stores/cart';
import { useAuthStore } from '@/stores/auth';
import Navbar from '@/components/common/Navbar.vue';
import Footer from '@/components/common/Footer.vue';
import BesmartAIChatbot from '@/components/ai/BesmartAIChatbot.vue';
import DiscountWheel from '@/components/gamification/DiscountWheel.vue';

const showWheelModal = ref(false);
const cartStore = useCartStore();
const authStore = useAuthStore();
</script>
