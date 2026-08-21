<template>
  <div class="bg-slate-50/50 min-h-screen">

    <!-- ===== 1. Full-Width Hero Carousel ===== -->
    <section class="hero-carousel" id="hero-carousel">
      <div class="hero-carousel__track" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
        <div v-for="(slide, i) in heroSlides" :key="i" class="hero-carousel__slide">
          <div
            :style="{ background: slide.bg, minHeight: '380px' }"
            class="w-full flex items-center justify-center px-6 md:px-16 relative overflow-hidden py-12 md:py-0"
            style="min-height: 380px;"
          >
            <!-- Decorative blurred glow blobs -->
            <div class="absolute -right-16 -top-16 w-80 h-80 rounded-full blur-3xl opacity-30 animate-pulse" :style="{ background: slide.accent }"></div>
            <div class="absolute -left-10 -bottom-10 w-60 h-60 rounded-full blur-3xl opacity-20" :style="{ background: slide.accent }"></div>

            <div class="max-w-7xl mx-auto w-full grid grid-cols-1 md:grid-cols-12 gap-8 items-center relative z-10">
              <div class="md:col-span-7 text-left">
                <span v-if="slide.badge" class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold mb-4 shadow-sm backdrop-blur-md" :style="{ background: 'rgba(255,255,255,0.15)', color: '#fff', border: '1px solid rgba(255,255,255,0.2)' }">
                  <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                  {{ slide.badge }}
                </span>
                <h2 class="text-3xl md:text-5xl font-extrabold mb-4 leading-tight tracking-tight drop-shadow-sm" :style="{ color: slide.textColor || '#fff' }">
                  {{ slide.title }}
                </h2>
                <p class="text-sm md:text-lg mb-6 opacity-90 leading-relaxed max-w-xl" :style="{ color: slide.textColor || '#fff' }">
                  {{ slide.subtitle }}
                </p>
                <router-link :to="slide.link" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-lg font-bold text-sm transition-all hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0"
                  :style="{ background: slide.btnBg || '#fff', color: slide.btnColor || '#f85606' }">
                  {{ slide.btnText }}
                  <span class="text-base">→</span>
                </router-link>
              </div>

              <!-- Premium Product Overlay Graphic -->
              <div class="md:col-span-5 hidden md:flex justify-center items-center relative h-64">
                <div class="absolute inset-0 bg-white/5 rounded-full blur-2xl transform scale-75"></div>
                <img
                  :src="slide.image"
                  @error="handleImageError"
                  :alt="slide.title"
                  class="max-h-56 object-contain drop-shadow-2xl transform hover:scale-105 transition-transform duration-700 animate-float"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
      <button @click="prevSlide" class="hero-carousel__arrow hero-carousel__arrow--prev" aria-label="Previous slide">‹</button>
      <button @click="nextSlide" class="hero-carousel__arrow hero-carousel__arrow--next" aria-label="Next slide">›</button>
      <div class="hero-carousel__dots">
        <button v-for="(_, i) in heroSlides" :key="i"
          @click="goToSlide(i)"
          class="hero-carousel__dot"
          :class="{ 'hero-carousel__dot--active': currentSlide === i }"
          :aria-label="`Go to slide ${i + 1}`"
        ></button>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

      <!-- ===== 2. Trust Badges Row ===== -->
      <section class="trust-badges animate-slide-up" id="trust-badges">
        <div class="trust-badge">
          <div class="trust-badge__icon-wrapper bg-orange-50 text-brand-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          </div>
          <div class="trust-badge__content">
            <span class="trust-badge__title">Competitive Price</span>
            <span class="trust-badge__desc">Direct wholesale savings</span>
          </div>
        </div>
        <div class="trust-badge">
          <div class="trust-badge__icon-wrapper bg-emerald-50 text-emerald-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 11 2 2 4-4"/></svg>
          </div>
          <div class="trust-badge__content">
            <span class="trust-badge__title">100% Authentic</span>
            <span class="trust-badge__desc">Genuine products only</span>
          </div>
        </div>
        <div class="trust-badge">
          <div class="trust-badge__icon-wrapper bg-indigo-50 text-indigo-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          </div>
          <div class="trust-badge__content">
            <span class="trust-badge__title">Secure Checkout</span>
            <span class="trust-badge__desc">Encrypted payment portal</span>
          </div>
        </div>
        <div class="trust-badge">
          <div class="trust-badge__icon-wrapper bg-sky-50 text-sky-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="2" ry="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
          </div>
          <div class="trust-badge__content">
            <span class="trust-badge__title">Express Delivery</span>
            <span class="trust-badge__desc">Doorstep shipment tracking</span>
          </div>
        </div>
      </section>

      <!-- ===== 3. Promo Banner Strip ===== -->
      <section class="promo-banner animate-slide-up" id="promo-banner">
        <div class="w-full rounded-2xl overflow-hidden relative" style="background: linear-gradient(135deg, #f85606 0%, #ff8340 50%, #f43f5e 100%); padding: 36px 40px; box-shadow: 0 10px 30px -10px rgba(248, 86, 6, 0.3);">
          <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-white/10 via-transparent to-transparent"></div>
          <div class="flex items-center justify-between flex-wrap gap-6 relative z-10">
            <div>
              <span class="inline-block px-3 py-1 bg-white/20 text-white text-[11px] font-bold rounded-full mb-3 uppercase tracking-wider">Mega Campaign</span>
              <h3 class="text-white text-3xl md:text-4xl font-extrabold tracking-tight">Super Deals Up to 70% OFF!</h3>
              <p class="text-white/90 text-sm md:text-base mt-2 max-w-xl font-medium">Limited quantities available — claim your hot vouchers before they run out.</p>
            </div>
            <router-link to="/shop" class="px-8 py-4 bg-white text-brand-500 font-extrabold rounded-xl text-sm shadow-md hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300">
              Claim Discount Voucher →
            </router-link>
          </div>
        </div>
      </section>

      <!-- ===== 4. Flash Sale Section ===== -->
      <section v-if="flashSales.length" id="flash-sale-section" class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <div class="flash-sale-header border-b border-slate-100 pb-4 mb-5">
          <div class="flash-sale-title">
            <div class="flex items-center gap-2">
              <span class="text-2xl animate-bounce">⚡</span>
              <h2>FLASH SALE</h2>
            </div>
            <div class="countdown ml-2">
              <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mr-2.5">Ending In</span>
              <span class="countdown__block shadow-sm">{{ padZero(countdown.hours) }}</span>
              <span class="countdown__sep">:</span>
              <span class="countdown__block shadow-sm">{{ padZero(countdown.minutes) }}</span>
              <span class="countdown__sep">:</span>
              <span class="countdown__block shadow-sm">{{ padZero(countdown.seconds) }}</span>
            </div>
          </div>
          <router-link to="/shop" class="section-header__link">
            Shop More
          </router-link>
        </div>

        <div class="h-scroll-carousel">
          <div v-for="product in flashSales" :key="product.id" class="flash-card border border-slate-100">
            <!-- Discount Badge -->
            <span v-if="getDiscount(product) > 0" class="discount-badge shadow-md">
              -{{ getDiscount(product) }}%
            </span>

            <router-link :to="{ name: 'product-detail', params: { id: product.id } }" class="block aspect-square overflow-hidden bg-slate-50 relative group/img">
              <img
                :src="product.primary_image?.image_url || product.image_url || defaultFallback"
                @error="handleImageError"
                :alt="product.name"
                class="flash-card__img group-hover/img:scale-105 transition-transform duration-300"
                loading="lazy"
              />
            </router-link>

            <div class="flash-card__body">
              <h4 class="text-xs text-slate-700 font-semibold line-clamp-1 mb-1.5 hover:text-brand-500 transition-colors">
                <router-link :to="{ name: 'product-detail', params: { id: product.id } }">
                  {{ product.name }}
                </router-link>
              </h4>
              <div class="flex items-baseline gap-1">
                <span class="flash-card__price">৳{{ (product.sale_price || product.price).toLocaleString() }}</span>
                <span v-if="product.sale_price && product.sale_price < product.price" class="flash-card__original">৳{{ product.price.toLocaleString() }}</span>
              </div>

              <!-- Stock Progress -->
              <div class="flash-card__progress mb-2">
                <div class="flash-card__progress-bar">
                  <div class="flash-card__progress-fill" :style="{ width: getStockProgress(product) + '%' }"></div>
                  <span class="flash-card__progress-text">{{ getStockLabel(product) }}</span>
                </div>
              </div>

              <!-- CTA Button -->
              <button
                @click="cartStore.addToCart(product.id, 1)"
                class="w-full py-2 rounded-xl bg-brand-500 hover:bg-brand-600 active:scale-95 text-white font-extrabold text-[11px] uppercase tracking-wider flex items-center justify-center gap-1.5 shadow-sm transition-all"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
                Add to Cart
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- ===== 5. Categories Grid ===== -->
      <section id="categories-section" class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <div class="section-header border-b border-slate-100 pb-4 mb-6">
          <h2>Shop by Category</h2>
          <router-link to="/shop" class="section-header__link">
            View All
          </router-link>
        </div>

        <div class="categories-grid">
          <router-link
            v-for="cat in displayCategories"
            :key="cat.id"
            :to="{ name: 'shop', query: { category_id: cat.id } }"
            class="category-tile group"
          >
            <div class="category-tile__icon-container bg-slate-50 group-hover:bg-brand-50 group-hover:text-brand-500 transition-all duration-300 shadow-sm border border-slate-100">
              <span v-html="getCategorySvg(cat.name)"></span>
            </div>
            <div class="category-tile__label group-hover:text-brand-500 transition-colors">{{ cat.name }}</div>
          </router-link>
        </div>
      </section>

      <!-- ===== 6. Deals You Can't Miss ===== -->
      <section id="deals-section" class="space-y-6">
        <div class="section-header border-b border-slate-200 pb-4">
          <h2>🔥 Deals You Can't Miss</h2>
          <router-link to="/shop" class="section-header__link">
            View All Deals
          </router-link>
        </div>

        <div class="product-grid">
          <ProductCard v-for="product in featured" :key="product.id" :product="product" />
        </div>
      </section>

      <!-- ===== 7. SEO / Location Footer Content ===== -->
      <section class="bg-white rounded-2xl border border-slate-100 p-8 text-sm text-slate-500 leading-relaxed shadow-sm" id="seo-content">
        <h3 class="font-extrabold text-slate-800 text-lg mb-3">Welcome to Besmart — Bangladesh's Premier E-Commerce Marketplace</h3>
        <p class="mb-3">
          Besmart connects millions of shoppers across South Asia with leading manufacturers, premium global brands, and verified suppliers. We feature direct wholesale pricing tiers, a real-time landed cost import calculator, and advanced AI-assisted shopping configurations.
        </p>
        <p class="mb-4">
          Our robust logistics network guarantees authentic product deliveries, comprehensive buyer protection plans, and seamless payment collection protocols via SSLCommerz, bKash, and cash-on-delivery channels.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-100 text-xs font-semibold text-slate-400">
          <div>
            <strong>Corporate HQ:</strong> House 42, Road 11, Block D, Banani, Dhaka-1213, Bangladesh.
          </div>
          <div class="md:text-right">
            Trade License: TRAD/DNCC/2024/00001 | VAT Reg: 000000001 | Registered under Companies Act 1994
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted, computed } from 'vue';
import axios from 'axios';
import ProductCard from '@/components/storefront/ProductCard.vue';
import { useAuthStore } from '@/stores/auth';
import { useCartStore } from '@/stores/cart';

const authStore = useAuthStore();
const cartStore = useCartStore();
const defaultFallback = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80';

function handleImageError(e) {
  e.target.src = defaultFallback;
}

// ===== Data =====
const categories = ref([]);
const featured = ref([]);
const flashSales = ref([]);

// ===== Hero Carousel =====
const currentSlide = ref(0);
let slideInterval = null;

const heroSlides = [
  {
    title: 'Super Campaign is LIVE!',
    subtitle: 'Get up to 70% OFF on premium gadgets, smart essentials, and wardrobe collections. Free shipping applies above ৳2000.',
    badge: '🔥 FLASH PROMO',
    bg: 'linear-gradient(135deg, #f85606 0%, #ff6b35 100%)',
    accent: '#ef4444',
    textColor: '#fff',
    btnBg: '#fff',
    btnColor: '#f85606',
    btnText: 'Shop Vouchers',
    link: '/shop',
    image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80',
  },
  {
    title: 'Elite Sound Acoustics',
    subtitle: 'Experience true immersion with 35dB Active Noise Cancellation and 40h high-fidelity playback wireless headphones.',
    badge: '🎧 AUDIO INSIDER',
    bg: 'linear-gradient(135deg, #1e1b4b 0%, #312e81 100%)',
    accent: '#6366f1',
    textColor: '#fff',
    btnBg: '#f85606',
    btnColor: '#fff',
    btnText: 'Shop Acoustics',
    link: '/shop?category_id=2',
    image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80',
  },
  {
    title: 'Ultimate Gaming Rig',
    subtitle: 'Deploy RTX 4080 graphics, 240Hz OLED panels, and high-density liquid cooling architectures on new gaming flagships.',
    badge: '💻 TECH EXPO',
    bg: 'linear-gradient(135deg, #0f172a 0%, #1e293b 100%)',
    accent: '#3b82f6',
    textColor: '#fff',
    btnBg: '#3b82f6',
    btnColor: '#fff',
    btnText: 'Explore Gear',
    link: '/shop?category_id=5',
    image: 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=600&q=80',
  },
  {
    title: 'Modern Living Spaces',
    subtitle: 'Upgrade your kitchen, smart sanitation devices, and comfort furniture configurations with authentic brand assurances.',
    badge: '🏠 CASA LIVING',
    bg: 'linear-gradient(135deg, #064e3b 0%, #065f46 100%)',
    accent: '#10b981',
    textColor: '#fff',
    btnBg: '#fff',
    btnColor: '#064e3b',
    btnText: 'Shop Interiors',
    link: '/shop?category_id=7',
    image: 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=600&q=80',
  },
  {
    title: 'High-Speed Logistics',
    subtitle: 'Fast charging accessories, GaN hubs, and robust adapters designed to power enterprise devices with safe current distribution.',
    badge: '⚡ CHARGE HUB',
    bg: 'linear-gradient(135deg, #78350f 0%, #92400e 100%)',
    accent: '#f59e0b',
    textColor: '#fff',
    btnBg: '#fff',
    btnColor: '#78350f',
    btnText: 'Grab Chargers',
    link: '/shop?category_id=11',
    image: 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=600&q=80',
  },
  {
    title: 'Professional Aerials',
    subtitle: 'Seize aerial photography with 3-axis gimbal cameras, smart obstacle avoidance, and ultra-long range transmission modules.',
    badge: '🚁 ROTOR TECH',
    bg: 'linear-gradient(135deg, #4c1d95 0%, #5b21b6 100%)',
    accent: '#8b5cf6',
    textColor: '#fff',
    btnBg: '#fbbf24',
    btnColor: '#4c1d95',
    btnText: 'Launch Drone',
    link: '/shop?category_id=3',
    image: 'https://images.unsplash.com/photo-1507582195869-42c77ec33a36?w=600&q=80',
  },
];

function nextSlide() {
  currentSlide.value = (currentSlide.value + 1) % heroSlides.length;
}
function prevSlide() {
  currentSlide.value = (currentSlide.value - 1 + heroSlides.length) % heroSlides.length;
}
function goToSlide(i) {
  currentSlide.value = i;
}
function startAutoSlide() {
  slideInterval = setInterval(nextSlide, 5000);
}
function stopAutoSlide() {
  if (slideInterval) clearInterval(slideInterval);
}

// ===== Countdown Timer =====
const countdown = reactive({ hours: 0, minutes: 0, seconds: 0 });
let countdownInterval = null;

function initCountdown() {
  const now = new Date();
  const endOfDay = new Date(now);
  endOfDay.setHours(23, 59, 59, 999);
  let remaining = Math.floor((endOfDay - now) / 1000);

  function tick() {
    if (remaining <= 0) {
      remaining = 86400;
    }
    countdown.hours = Math.floor(remaining / 3600);
    countdown.minutes = Math.floor((remaining % 3600) / 60);
    countdown.seconds = remaining % 60;
    remaining--;
  }
  tick();
  countdownInterval = setInterval(tick, 1000);
}

// ===== Category SVG Picker =====
function getCategorySvg(name) {
  const cleaned = name.toLowerCase();
  
  if (cleaned.includes('laptop') || cleaned.includes('computer')) {
    return `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>`;
  }
  if (cleaned.includes('phone') || cleaned.includes('tablet')) {
    return `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>`;
  }
  if (cleaned.includes('audio') || cleaned.includes('headphone')) {
    return `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>`;
  }
  if (cleaned.includes('wearable') || cleaned.includes('watch')) {
    return `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="7"/><polyline points="12 9 12 12 13.5 13.5"/><path d="M16.51 7.49 19 4M7.49 16.51 4 19M16.51 16.51 19 19M7.49 7.49 4 4"/></svg>`;
  }
  if (cleaned.includes('game') || cleaned.includes('gaming') || cleaned.includes('gear')) {
    return `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="3"/><path d="M6 12h4M8 10v4"/><circle cx="15" cy="11" r="1"/><circle cx="18" cy="13" r="1"/></svg>`;
  }
  if (cleaned.includes('fashion') || cleaned.includes('clothing')) {
    return `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.38 3.46 16 7.84V4a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3.84L3.62 3.46a1 1 0 0 0-1.41.08L1.08 4.95a1 1 0 0 0 .08 1.41L6 11v9a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-9l4.84-4.64a1 1 0 0 0 .08-1.41L21.79 3.54a1 1 0 0 0-1.41-.08z"/></svg>`;
  }
  if (cleaned.includes('home') || cleaned.includes('living')) {
    return `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>`;
  }
  if (cleaned.includes('beauty') || cleaned.includes('health') || cleaned.includes('care')) {
    return `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>`;
  }
  if (cleaned.includes('sport') || cleaned.includes('outdoor')) {
    return `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M6 12a6 6 0 0 1 12 0A6 6 0 0 1 6 12z"/></svg>`;
  }
  if (cleaned.includes('grocery') || cleaned.includes('groceries') || cleaned.includes('food')) {
    return `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>`;
  }
  if (cleaned.includes('baby') || cleaned.includes('toy')) {
    return `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/></svg>`;
  }
  if (cleaned.includes('auto') || cleaned.includes('vehicle') || cleaned.includes('car')) {
    return `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="22" height="13" rx="2"/><path d="M14 9h4v3h-4zM6 9h4v3H6z"/><circle cx="6.5" cy="18.5" r="2.5"/><circle cx="17.5" cy="18.5" r="2.5"/></svg>`;
  }
  if (cleaned.includes('book') || cleaned.includes('stationery') || cleaned.includes('office')) {
    return `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>`;
  }
  if (cleaned.includes('appliance') || cleaned.includes('power') || cleaned.includes('charger')) {
    return `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><path d="M12 18h.01"/><path d="M9 6h6v4H9z"/></svg>`;
  }
  if (cleaned.includes('camera') || cleaned.includes('drone') || cleaned.includes('security')) {
    return `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>`;
  }
  return `<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M21 16V8a2 2 0 0 0-2-2h-5l-2-3-2 3H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2z"/></svg>`;
}

function padZero(n) {
  return String(n).padStart(2, '0');
}

// ===== Fallback Categories with Names matching DB Seeder categories precisely =====
const fallbackCategories = [
  { id: 1, name: 'Computers & Laptops' },
  { id: 2, name: 'Mobile & Tablets' },
  { id: 3, name: 'Audio & Headphones' },
  { id: 4, name: 'Cameras & Drones' },
  { id: 5, name: 'PC Components' },
  { id: 6, name: 'Monitors' },
  { id: 7, name: 'Gaming Mice & Keyboards' },
  { id: 8, name: 'Power Banks & Chargers' },
  { id: 9, name: 'Cables & Adapters' },
  { id: 10, name: 'Smart Lighting' },
  { id: 11, name: 'Security Cameras' },
  { id: 12, name: 'Robotic Vacuums' },
  { id: 13, name: 'Smart Home' },
  { id: 14, name: 'Accessories' }
];

const displayCategories = computed(() => {
  if (categories.value.length > 0) {
    return categories.value;
  }
  return fallbackCategories;
});

// ===== Fallback Products (13 products matching the database seeder precisely) =====
const fallbackProducts = [
  {
    id: 1,
    name: 'ProGear Stealth X Pro Gaming Laptop 16"',
    price: 145000,
    sale_price: 139900,
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=500&q=80',
    category: { name: 'Gaming Laptops' },
  },
  {
    id: 2,
    name: 'OmniSound ANC Wireless Headphones Pro',
    price: 12500,
    sale_price: 10900,
    is_flash_sale: false,
    image_url: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80',
    category: { name: 'Audio & Headphones' },
  },
  {
    id: 3,
    name: 'NexusTech Ergonomic Wireless RGB Mouse',
    price: 2800,
    sale_price: 2400,
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=500&q=80',
    category: { name: 'Gaming Mice & Keyboards' },
  },
  {
    id: 4,
    name: 'AeroPulse Fast Charge 100W Power Bank 25000mAh',
    price: 4500,
    sale_price: 3900,
    is_flash_sale: false,
    image_url: 'https://images.unsplash.com/photo-1609592424109-dd9892f1b177?w=500&q=80',
    category: { name: 'Power Banks & Chargers' },
  },
  {
    id: 5,
    name: 'Vanguard 4K UltraHD Curved Gaming Monitor 27"',
    price: 38000,
    sale_price: 34500,
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=500&q=80',
    category: { name: 'Monitors' },
  },
  {
    id: 6,
    name: 'NexusTech Mechanical Gaming Keyboard Red Switch',
    price: 7500,
    sale_price: 6400,
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&q=80',
    category: { name: 'Gaming Mice & Keyboards' },
  },
  {
    id: 7,
    name: 'AeroPulse 65W GaN Fast Charger Dual Port',
    price: 3200,
    sale_price: 2600,
    is_flash_sale: false,
    image_url: 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=500&q=80',
    category: { name: 'Power Banks & Chargers' },
  },
  {
    id: 8,
    name: 'Vanguard Smart Security Camera 2K WiFi',
    price: 4800,
    sale_price: 3900,
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1558002038-1055907df827?w=500&q=80',
    category: { name: 'Security Cameras' },
  },
  {
    id: 9,
    name: 'ProGear HD Quadcopter Action Drone 4K',
    price: 85000,
    sale_price: 78000,
    is_flash_sale: false,
    image_url: 'https://images.unsplash.com/photo-1507582195869-42c77ec33a36?w=500&q=80',
    category: { name: 'Cameras & Drones' },
  },
  {
    id: 10,
    name: 'OmniSound TWS ANC Wireless Earbuds',
    price: 6500,
    sale_price: 5400,
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=500&q=80',
    category: { name: 'Audio & Headphones' },
  },
  {
    id: 11,
    name: 'Vanguard Smart Robotic Vacuum Cleaner Mop',
    price: 29500,
    sale_price: 26000,
    is_flash_sale: false,
    image_url: 'https://images.unsplash.com/photo-1589630972273-c3eed6f616f7?w=500&q=80',
    category: { name: 'Robotic Vacuums' },
  },
  {
    id: 12,
    name: 'NexusTech 10-in-1 USB-C Docking Station',
    price: 4200,
    sale_price: 3500,
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1468495244123-6c6c332eeece?w=500&q=80',
    category: { name: 'Cables & Adapters' },
  },
  {
    id: 13,
    name: 'NexusTech Streamer Full HD Autofocus Webcam',
    price: 5500,
    sale_price: 4500,
    is_flash_sale: false,
    image_url: 'https://images.unsplash.com/photo-1600541519463-ee374529f79c?w=500&q=80',
    category: { name: 'Mobile & Tablets' },
  },
];

// ===== Flash Sale Helpers =====
function getDiscount(product) {
  if (product.sale_price && product.price > product.sale_price) {
    return Math.round(((product.price - product.sale_price) / product.price) * 100);
  }
  return 0;
}

function getStockProgress(product) {
  const seed = (product.id * 37 + 13) % 100;
  return Math.min(95, Math.max(30, seed));
}

function getStockLabel(product) {
  const progress = getStockProgress(product);
  if (progress >= 90) return 'Stock Out!';
  if (progress >= 75) return `${Math.max(1, Math.floor((100 - progress) / 5))} items left`;
  if (progress >= 50) return 'Limited Stock!';
  return 'Selling Fast!';
}

// ===== Fetch Data =====
onMounted(async () => {
  startAutoSlide();
  initCountdown();

  try {
    const catRes = await axios.get('/api/v1/categories');
    categories.value = catRes.data.data?.length ? catRes.data.data : [];
  } catch (e) {
    categories.value = [];
  }

  try {
    const fRes = await axios.get('/api/v1/products/featured');
    featured.value = fRes.data.data?.length ? fRes.data.data : fallbackProducts;
  } catch (e) {
    featured.value = fallbackProducts;
  }

  try {
    const fsRes = await axios.get('/api/v1/products/flash-sales');
    flashSales.value = fsRes.data.data?.length ? fsRes.data.data : fallbackProducts.filter(p => p.is_flash_sale);
  } catch (e) {
    flashSales.value = fallbackProducts.filter(p => p.is_flash_sale);
  }
});

onUnmounted(() => {
  stopAutoSlide();
  if (countdownInterval) clearInterval(countdownInterval);
});
</script>
