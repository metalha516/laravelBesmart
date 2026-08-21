<template>
  <header class="sticky top-0 z-50">
    <!-- Tier 1: Top Utility Bar -->
    <div class="besmart-topbar hidden md:block">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <router-link to="/register?role=b2b" class="hover:text-brand-500 font-semibold">Sell on BeSmart</router-link>
          <span class="text-gray-300">|</span>
          <a href="#" class="hover:text-brand-500">Help & Support</a>
          <span class="text-gray-300">|</span>
          <button @click="$emit('open-wheel')" class="hover:text-brand-500 font-semibold flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364-.707.707M6.343 17.657l-.707.707m0-12.728.707.707m11.32 11.32-.707.707M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8z"/></svg>
            Spin & Win Discount!
          </button>
        </div>
        <div class="flex items-center gap-4">
          <router-link to="/saas/pricing" class="hover:text-brand-500 font-bold text-amber-600 dark:text-amber-400 flex items-center gap-1">
            <span>⚡</span> SaaS Merchant Plans
          </router-link>
          <span class="text-gray-300">|</span>
          <template v-if="authStore.isAuthenticated">
            <div class="relative group" @mouseleave="isUserMenuOpen = false">
              <button
                @click.stop="isUserMenuOpen = !isUserMenuOpen"
                @mouseenter="isUserMenuOpen = true"
                class="flex items-center gap-1.5 font-semibold hover:text-brand-500 cursor-pointer py-1"
                id="user-menu-dropdown-toggle"
              >
                <span class="w-5 h-5 rounded-full bg-brand-500 text-white text-[10px] font-bold flex items-center justify-center shadow-sm">
                  {{ authStore.user?.name?.charAt(0) }}
                </span>
                <span class="font-bold text-gray-800 dark:text-gray-200">{{ authStore.user?.name }}</span>
                <span class="text-[10px] text-gray-400">▼</span>
              </button>

              <div
                :class="[isUserMenuOpen ? 'block' : 'hidden', 'group-hover:block']"
                class="absolute right-0 top-full mt-1 w-52 bg-white dark:bg-gray-900 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 py-2 z-[99999] pointer-events-auto transition-all"
                id="user-menu-dropdown-options"
              >
                <router-link
                  v-if="authStore.isAdmin"
                  to="/admin/dashboard"
                  @click="isUserMenuOpen = false"
                  class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-gray-800 dark:text-gray-200 hover:bg-brand-50 dark:hover:bg-gray-800 hover:text-brand-600 transition-colors"
                >
                  <span class="text-brand-500">⚡</span> Admin Center
                </router-link>
                <router-link
                  v-if="authStore.isB2B"
                  to="/b2b/dashboard"
                  @click="isUserMenuOpen = false"
                  class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-gray-800 dark:text-gray-200 hover:bg-brand-50 dark:hover:bg-gray-800 hover:text-brand-600 transition-colors"
                >
                  <span class="text-amber-500">📊</span> B2B Dashboard
                </router-link>
                <router-link
                  to="/customer/dashboard"
                  @click="isUserMenuOpen = false"
                  class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-brand-50 dark:hover:bg-gray-800 hover:text-brand-600 transition-colors"
                >
                  <span class="text-blue-500">👤</span> My Account
                </router-link>
                <router-link
                  to="/cart"
                  @click="isUserMenuOpen = false"
                  class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-brand-50 dark:hover:bg-gray-800 hover:text-brand-600 transition-colors"
                  id="dropdown-option-my-orders"
                >
                  <span class="text-emerald-500">📦</span> My Orders
                </router-link>
                <hr class="my-1.5 border-gray-100 dark:border-gray-800" />
                <button
                  @click="isUserMenuOpen = false; authStore.logout(); $router.push('/')"
                  class="w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors cursor-pointer"
                  id="dropdown-option-sign-out"
                >
                  <span>🚪</span> Sign Out
                </button>
              </div>
            </div>
          </template>
          <template v-else>
            <router-link to="/login" class="hover:text-brand-500 font-semibold">Login</router-link>
            <span class="text-gray-300">|</span>
            <router-link to="/register" class="hover:text-brand-500 font-semibold">Sign Up</router-link>
          </template>
        </div>
      </div>
    </div>

    <!-- Tier 2: Main Nav (Logo + Search + Cart) -->
    <div class="besmart-main-header">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
        <!-- Logo -->
        <router-link to="/" class="flex items-center gap-2 flex-shrink-0">
          <div class="w-10 h-10 rounded-lg bg-brand-500 flex items-center justify-center text-white font-extrabold text-xl shadow-md">
            B
          </div>
          <div class="flex flex-col leading-none">
            <span class="font-extrabold text-2xl text-brand-500 tracking-tight">BeSmart</span>
            <span class="text-[9px] text-gray-400 font-semibold tracking-widest uppercase">MARKETPLACE</span>
          </div>
        </router-link>

        <!-- Search Bar (Desktop) -->
        <div class="besmart-search-bar hidden md:flex">
          <input
            v-model="searchQuery"
            @keyup.enter="handleSearch"
            type="text"
            placeholder="Search for products, brands, and more..."
          />
          <button @click="handleSearch" id="search-button" class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/></svg>
            Search
          </button>
        </div>

        <!-- Right Actions -->
        <div class="flex items-center gap-3">
          <!-- Cart -->
          <router-link to="/cart" class="relative flex flex-col items-center text-gray-600 hover:text-brand-500 transition-colors p-2" id="header-cart-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-slate-700 hover:text-brand-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
            <span v-if="cartStore.itemCount > 0" class="absolute top-0.5 right-0.5 min-w-[18px] h-[18px] rounded-full bg-brand-500 text-white text-[10px] font-bold flex items-center justify-center px-1 shadow-sm">
              {{ cartStore.itemCount }}
            </span>
          </router-link>

          <!-- Auth buttons (mobile) -->
          <template v-if="!authStore.isAuthenticated">
            <router-link to="/login" class="md:hidden px-3 py-1.5 rounded-md border border-brand-500 text-brand-500 text-xs font-bold">
              Login
            </router-link>
          </template>

          <!-- Hamburger Menu (Mobile) -->
          <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-2xl text-gray-600 hover:text-brand-500 p-2" id="mobile-menu-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
          </button>
        </div>
      </div>

      <!-- Mobile Search Bar -->
      <div class="md:hidden px-4 mt-2 pb-2">
        <div class="besmart-search-bar flex items-center">
          <input
            v-model="searchQuery"
            @keyup.enter="handleSearch"
            type="text"
            placeholder="Search products..."
            class="flex-grow bg-transparent border-none outline-none px-3 py-2 text-sm"
          />
          <button @click="handleSearch" class="p-2 mr-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <Transition name="slide">
      <div v-if="mobileMenuOpen" class="fixed inset-0 z-[200] md:hidden">
        <div class="absolute inset-0 bg-black/40" @click="mobileMenuOpen = false"></div>
        <div class="absolute left-0 top-0 bottom-0 w-72 bg-white shadow-2xl overflow-y-auto">
          <div class="p-4 bg-brand-500 text-white">
            <div class="flex items-center justify-between mb-3">
              <span class="font-bold text-lg">BeSmart</span>
              <button @click="mobileMenuOpen = false" class="text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
              </button>
            </div>
            <template v-if="authStore.isAuthenticated">
              <p class="font-semibold">Hi, {{ authStore.user?.name }}</p>
            </template>
            <template v-else>
              <div class="flex gap-2">
                <router-link to="/login" @click="mobileMenuOpen = false" class="flex-1 text-center py-2 rounded bg-white text-brand-500 font-bold text-sm">Login</router-link>
                <router-link to="/register" @click="mobileMenuOpen = false" class="flex-1 text-center py-2 rounded bg-white/20 text-white font-bold text-sm border border-white/30">Sign Up</router-link>
              </div>
            </template>
          </div>
          <nav class="py-2">
            <router-link to="/" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-500 border-b border-gray-100">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 group-hover:text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
              Home
            </router-link>
            <router-link to="/shop" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-500 border-b border-gray-100">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 group-hover:text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
              All Products
            </router-link>
            <router-link to="/cart" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-500 border-b border-gray-100">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
              Cart ({{ cartStore.itemCount }})
            </router-link>
            <template v-if="authStore.isAuthenticated">
              <router-link to="/customer/dashboard" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-500 border-b border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                My Account
              </router-link>
              <router-link v-if="authStore.isB2B" to="/b2b/dashboard" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-500 border-b border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z"/></svg>
                B2B Dashboard
              </router-link>
              <router-link v-if="authStore.isAdmin" to="/admin/dashboard" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-brand-500 border-b border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.214-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.43l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.75-.43-.991l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.645-.869L9.594 3.94ZM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/></svg>
                Admin Center
              </router-link>
              <button @click="authStore.logout(); mobileMenuOpen = false; $router.push('/')" class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-500 hover:bg-gray-50 border-b border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                Sign Out
              </button>
            </template>
          </nav>
        </div>
      </div>
    </Transition>

    <!-- Mobile Sticky Bottom Navigation Bar (Android & iOS Phone Friendly) -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 py-1.5 px-3 flex justify-around items-center shadow-lg text-[10px] font-bold text-slate-600">
      <router-link to="/" class="flex flex-col items-center gap-0.5 hover:text-brand-500" :class="[ $route.path === '/' ? 'text-brand-500 font-extrabold' : '' ]">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
        <span>Home</span>
      </router-link>

      <router-link to="/shop" class="flex flex-col items-center gap-0.5 hover:text-brand-500" :class="[ $route.path === '/shop' ? 'text-brand-500 font-extrabold' : '' ]">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
        <span>Shop</span>
      </router-link>

      <router-link to="/cart" class="flex flex-col items-center gap-0.5 relative hover:text-brand-500" :class="[ $route.path === '/cart' ? 'text-brand-500 font-extrabold' : '' ]">
        <div class="relative">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
          <span v-if="cartStore.itemCount > 0" class="absolute -top-1.5 -right-2 w-4 h-4 rounded-full bg-brand-500 text-white text-[9px] font-bold flex items-center justify-center">
            {{ cartStore.itemCount }}
          </span>
        </div>
        <span>Cart</span>
      </router-link>

      <button @click="$emit('open-wheel')" class="flex flex-col items-center gap-0.5 text-amber-500 hover:text-amber-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364-.707.707M6.343 17.657l-.707.707m0-12.728.707.707m11.32 11.32-.707.707M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8z"/></svg>
        <span>Spin</span>
      </button>

      <router-link v-if="authStore.isAdmin" to="/admin/dashboard" class="flex flex-col items-center gap-0.5 text-purple-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z"/></svg>
        <span>Admin</span>
      </router-link>
      <router-link v-else-if="authStore.isAuthenticated" to="/customer/dashboard" class="flex flex-col items-center gap-0.5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
        <span>Account</span>
      </router-link>
      <router-link v-else to="/login" class="flex flex-col items-center gap-0.5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
        <span>Login</span>
      </router-link>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { useCartStore } from '@/stores/cart';

const router = useRouter();
const authStore = useAuthStore();
const cartStore = useCartStore();

const searchQuery = ref('');
const mobileMenuOpen = ref(false);
const isUserMenuOpen = ref(false);

function handleSearch() {
  if (searchQuery.value.trim()) {
    router.push({ name: 'shop', query: { search: searchQuery.value } });
    mobileMenuOpen.value = false;
  }
}
</script>

<style scoped>
.slide-enter-active, .slide-leave-active {
  transition: all 0.3s ease;
}
.slide-enter-from .absolute.left-0,
.slide-leave-to .absolute.left-0 {
  transform: translateX(-100%);
}
.slide-enter-from, .slide-leave-to {
  opacity: 0;
}
</style>
