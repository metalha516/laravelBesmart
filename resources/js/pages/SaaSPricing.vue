<template>
  <div class="max-w-6xl mx-auto py-10 px-4 space-y-10">
    <!-- Header -->
    <div class="text-center space-y-4 max-w-2xl mx-auto">
      <span class="px-4 py-1.5 rounded-full bg-brand-500/10 text-brand-500 font-extrabold text-xs tracking-wider uppercase border border-brand-500/20">
        ⚡ BeSmart SaaS Commerce Suite
      </span>
      <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-tight">
        Scale Your E-Commerce Store with Enterprise SaaS Tools
      </h1>
      <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400">
        Launch your online storefront, automate B2B wholesale pricing, calculate China import landed costs, and leverage AI assistant chatbots in minutes.
      </p>

      <!-- Billing Cycle Toggle -->
      <div class="inline-flex items-center p-1 rounded-2xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 mt-4">
        <button
          @click="isAnnual = false"
          :class="[ !isAnnual ? 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm font-black' : 'text-gray-400 font-bold' ]"
          class="px-5 py-2 rounded-xl text-xs transition-all"
        >
          Monthly Billing
        </button>
        <button
          @click="isAnnual = true"
          :class="[ isAnnual ? 'bg-brand-500 text-white shadow-sm font-black' : 'text-gray-400 font-bold' ]"
          class="px-5 py-2 rounded-xl text-xs transition-all flex items-center gap-1.5"
        >
          <span>Annual Billing</span>
          <span class="px-2 py-0.5 rounded-full bg-amber-400 text-gray-950 font-black text-[9px] uppercase">Save 20%</span>
        </button>
      </div>
    </div>

    <!-- Pricing Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- Starter Free Plan -->
      <div class="glass-card rounded-3xl p-8 border border-gray-200 dark:border-gray-800 flex flex-col justify-between hover:border-slate-300 dark:hover:border-slate-700 transition-all duration-300 shadow-xl">
        <div>
          <div class="flex items-center justify-between mb-4">
            <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-black text-xs">STARTER</span>
            <span class="text-xs font-bold text-gray-400">For New Stores</span>
          </div>
          <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Free Storefront</h3>
          <p class="text-xs text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">Essential tools to launch your online store and sell your first 50 items.</p>
          <div class="flex items-baseline gap-1 mb-6">
            <span class="text-4xl font-black text-gray-900 dark:text-white">৳0</span>
            <span class="text-xs font-bold text-gray-400">/ forever</span>
          </div>

          <!-- Feature List -->
          <ul class="space-y-3 text-xs text-gray-600 dark:text-gray-300 mb-8">
            <li class="flex items-center gap-2 font-semibold">
              <span class="text-emerald-500">✓</span> <span>Up to 50 Storefront Products</span>
            </li>
            <li class="flex items-center gap-2 font-semibold">
              <span class="text-emerald-500">✓</span> <span>Standard Responsive Storefront</span>
            </li>
            <li class="flex items-center gap-2 font-semibold">
              <span class="text-emerald-500">✓</span> <span>SSLCommerz & COD Payments</span>
            </li>
            <li class="flex items-center gap-2 font-semibold">
              <span class="text-emerald-500">✓</span> <span>Basic Order Management</span>
            </li>
            <li class="flex items-center gap-2 text-gray-400 line-through">
              <span>✕</span> <span>B2B Wholesale Tiers Engine</span>
            </li>
            <li class="flex items-center gap-2 text-gray-400 line-through">
              <span>✕</span> <span>China Import Landed Cost Calculator</span>
            </li>
          </ul>
        </div>

        <button
          @click="selectPlan('Starter Free')"
          class="w-full py-3.5 rounded-2xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-white font-extrabold text-xs uppercase tracking-wider transition-all cursor-pointer"
        >
          Get Started Free →
        </button>
      </div>

      <!-- Pro Merchant Plan (Featured) -->
      <div class="glass-card rounded-3xl p-8 border-2 border-brand-500 bg-gradient-to-b from-brand-500/5 via-transparent to-transparent flex flex-col justify-between shadow-2xl relative">
        <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-brand-500 text-white font-black text-[10px] uppercase tracking-wider shadow-md">
          🔥 Most Popular Merchant Plan
        </span>
        <div>
          <div class="flex items-center justify-between mb-4 mt-2">
            <span class="px-3 py-1 rounded-full bg-brand-500/20 text-brand-500 font-black text-xs">PRO MERCHANT</span>
            <span class="text-xs font-bold text-amber-500">High Growth</span>
          </div>
          <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Growth & B2B Suite</h3>
          <p class="text-xs text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">Full B2B wholesale pricing, custom domain, and automated discount wheels.</p>
          <div class="flex items-baseline gap-1 mb-6">
            <span class="text-4xl font-black text-brand-500">৳{{ isAnnual ? '3,920' : '4,900' }}</span>
            <span class="text-xs font-bold text-gray-400">/ month</span>
          </div>

          <!-- Feature List -->
          <ul class="space-y-3 text-xs text-gray-700 dark:text-gray-200 mb-8">
            <li class="flex items-center gap-2 font-bold">
              <span class="text-brand-500">✓</span> <span>Unlimited Catalog Products</span>
            </li>
            <li class="flex items-center gap-2 font-bold">
              <span class="text-brand-500">✓</span> <span>B2B Volume Wholesale Tiers</span>
            </li>
            <li class="flex items-center gap-2 font-bold">
              <span class="text-brand-500">✓</span> <span>Custom Subdomain & CNAME Settings</span>
            </li>
            <li class="flex items-center gap-2 font-bold">
              <span class="text-brand-500">✓</span> <span>Gamified Spin & Win Wheel</span>
            </li>
            <li class="flex items-center gap-2 font-bold">
              <span class="text-brand-500">✓</span> <span>0% Platform Transaction Fee</span>
            </li>
            <li class="flex items-center gap-2 font-bold">
              <span class="text-brand-500">✓</span> <span>Priority Support & Live Chat</span>
            </li>
          </ul>
        </div>

        <button
          @click="selectPlan('Pro Merchant')"
          class="w-full py-4 rounded-2xl bg-brand-500 hover:bg-brand-600 active:scale-95 text-white font-black text-xs uppercase tracking-wider shadow-glow transition-all cursor-pointer"
        >
          Subscribe to Pro Merchant →
        </button>
      </div>

      <!-- Enterprise SaaS Plan -->
      <div class="glass-card rounded-3xl p-8 border border-purple-500/30 bg-gradient-to-b from-purple-500/5 via-transparent to-transparent flex flex-col justify-between shadow-xl">
        <div>
          <div class="flex items-center justify-between mb-4">
            <span class="px-3 py-1 rounded-full bg-purple-500/20 text-purple-400 font-black text-xs">ENTERPRISE</span>
            <span class="text-xs font-bold text-purple-400">Multi-Tenant</span>
          </div>
          <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Cross-Border SaaS</h3>
          <p class="text-xs text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">Multi-store manager, China Landed Cost API, and white-label AI Assistant.</p>
          <div class="flex items-baseline gap-1 mb-6">
            <span class="text-4xl font-black text-purple-400">৳{{ isAnnual ? '15,920' : '19,900' }}</span>
            <span class="text-xs font-bold text-gray-400">/ month</span>
          </div>

          <!-- Feature List -->
          <ul class="space-y-3 text-xs text-gray-600 dark:text-gray-300 mb-8">
            <li class="flex items-center gap-2 font-bold">
              <span class="text-purple-400">✓</span> <span>Multi-Tenant Multi-Store Manager</span>
            </li>
            <li class="flex items-center gap-2 font-bold">
              <span class="text-purple-400">✓</span> <span>China Landed Cost Calculator API</span>
            </li>
            <li class="flex items-center gap-2 font-bold">
              <span class="text-purple-400">✓</span> <span>White-Label AI Shopping Assistant</span>
            </li>
            <li class="flex items-center gap-2 font-bold">
              <span class="text-purple-400">✓</span> <span>Dedicated Webhooks & REST APIs</span>
            </li>
            <li class="flex items-center gap-2 font-bold">
              <span class="text-purple-400">✓</span> <span>Dedicated SaaS Account Manager</span>
            </li>
          </ul>
        </div>

        <button
          @click="selectPlan('Enterprise SaaS')"
          class="w-full py-3.5 rounded-2xl bg-purple-600 hover:bg-purple-700 text-white font-extrabold text-xs uppercase tracking-wider transition-all cursor-pointer"
        >
          Contact Enterprise Sales →
        </button>
      </div>
    </div>

    <!-- Active Subscription Confirmation Modal -->
    <div v-if="selectedPlanModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
      <div class="glass-card rounded-3xl p-8 max-w-md w-full border border-gray-200 dark:border-gray-800 space-y-6 text-center">
        <div class="w-14 h-14 rounded-full bg-brand-500/20 text-brand-500 flex items-center justify-center mx-auto text-2xl font-black">
          🚀
        </div>
        <div>
          <h3 class="text-2xl font-black text-gray-900 dark:text-white">Subscribe to {{ selectedPlanModal }}</h3>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Configure your BeSmart SaaS Merchant Storefront</p>
        </div>

        <div class="space-y-3 text-left">
          <div>
            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Store Subdomain</label>
            <div class="flex items-center rounded-xl overflow-hidden border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-xs">
              <input v-model="storeSlug" type="text" placeholder="my-store" class="px-3 py-2.5 bg-transparent flex-1 font-bold text-gray-900 dark:text-white outline-none" />
              <span class="px-3 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-500 font-semibold">.besmart.com.bd</span>
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Payment Method</label>
            <select v-model="paymentChannel" class="w-full px-3 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-xs font-bold text-gray-900 dark:text-white">
              <option value="bKash">bKash Merchant Payment</option>
              <option value="Nagad">Nagad Online Billing</option>
              <option value="SSLCommerz">SSLCommerz International Cards</option>
            </select>
          </div>
        </div>

        <div class="flex gap-3">
          <button @click="selectedPlanModal = null" class="flex-1 py-3 rounded-xl bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-bold text-xs cursor-pointer">
            Cancel
          </button>
          <button @click="confirmSubscription" class="flex-1 py-3 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-extrabold text-xs shadow-glow cursor-pointer">
            Confirm & Activate →
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useNotificationStore } from '@/stores/notification';

const router = useRouter();
const notify = useNotificationStore();

const isAnnual = ref(false);
const selectedPlanModal = ref(null);
const storeSlug = ref('');
const paymentChannel = ref('bKash');

function selectPlan(planName) {
  selectedPlanModal.value = planName;
}

function confirmSubscription() {
  const finalSlug = storeSlug.value.trim() || 'tech-mart';
  notify.show(`Successfully subscribed store "${finalSlug}.besmart.com.bd" to ${selectedPlanModal.value}!`, 'success');
  selectedPlanModal.value = null;
  router.push({ name: 'admin-dashboard' });
}
</script>
