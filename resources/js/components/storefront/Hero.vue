<template>
  <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-brand-900 via-blue-900 to-indigo-950 text-white p-8 md:p-14 shadow-2xl border border-brand-800/50 my-6">
    <div class="relative z-10 max-w-2xl">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-500/20 border border-brand-400/30 text-brand-300 text-xs font-semibold mb-6">
        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
        <span>NEXT-GEN FULL-STACK PLATFORM</span>
      </div>

      <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight leading-tight mb-4">
        Shop Smart. <br />
        <span class="bg-gradient-to-r from-brand-300 via-blue-200 to-amber-300 bg-clip-text text-transparent">Buy Better.</span>
      </h1>

      <p class="text-gray-300 text-base md:text-lg mb-8 leading-relaxed">
        Enterprise retail e-commerce & wholesale B2B marketplace. Access quantity tier discounts, landed cost calculators, and AI shopping assistance.
      </p>

      <div class="flex flex-wrap items-center gap-4">
        <router-link to="/shop" class="px-7 py-3.5 rounded-2xl bg-gradient-to-r from-brand-500 to-blue-600 hover:from-brand-600 hover:to-blue-700 text-white font-bold text-sm shadow-glow transition-all transform hover:-translate-y-0.5">
          Explore Storefront →
        </router-link>
        <router-link to="/register?role=b2b" class="px-7 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm border border-white/20 backdrop-blur-md transition-all">
          B2B Wholesale Portal
        </router-link>
      </div>

      <div class="mt-10 pt-8 border-t border-white/10 grid grid-cols-3 gap-4 text-xs text-gray-300">
        <div>
          <span class="font-bold text-white text-base block">10-Year BI</span>
          <span>B2B Purchase Analytics</span>
        </div>
        <div>
          <span class="font-bold text-amber-300 text-base block">China Landed</span>
          <span>Import Cost Calculator</span>
        </div>
        <div>
          <span class="font-bold text-emerald-400 text-base block">Besmart AI</span>
          <span>Assistant Engine</span>
        </div>
      </div>
    </div>
  </div>
</template>
