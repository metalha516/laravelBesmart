<template>
  <div class="glass-card rounded-3xl p-3 sm:p-3.5 shadow-xl hover:shadow-2xl transition-all duration-300 flex flex-col justify-between h-full group" id="product-card">
    <div>
      <!-- Top Image Frame -->
      <div class="relative aspect-square rounded-2xl overflow-hidden bg-slate-100/60 mb-3 group/img">
        <!-- Top Left Badge (Discount or Best Seller) -->
        <span v-if="discountPercent > 0" class="absolute top-2.5 left-2.5 z-10 bg-rose-500 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-full shadow-md">
          -{{ discountPercent }}%
        </span>
        <span v-else class="absolute top-2.5 left-2.5 z-10 bg-white/90 backdrop-blur-md text-slate-900 text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">
          Best Seller
        </span>

        <!-- Top Right Brand/Logo Circle Badge -->
        <div class="absolute top-2.5 right-2.5 z-10 w-7 h-7 rounded-full bg-white/90 backdrop-blur-md shadow-md flex items-center justify-center text-brand-500 font-black text-xs border border-slate-200">
          B
        </div>

        <!-- Product Image -->
        <router-link :to="{ name: 'product-detail', params: { id: product.id } }" class="block w-full h-full">
          <img
            :src="product.primary_image?.image_url || product.image_url || defaultFallback"
            @error="handleImageError"
            :alt="product.name"
            class="w-full h-full object-cover object-center group-hover/img:scale-105 transition-transform duration-500"
            loading="lazy"
          />
        </router-link>

        <!-- Bottom Carousel Indicators -->
        <div class="absolute bottom-2.5 left-1/2 -translate-x-1/2 flex items-center gap-1 z-10 pointer-events-none opacity-80 group-hover/img:opacity-100 transition-opacity">
          <span class="w-3.5 h-1 rounded-full bg-white shadow-sm"></span>
          <span class="w-1 h-1 rounded-full bg-white/60"></span>
          <span class="w-1 h-1 rounded-full bg-white/60"></span>
          <span class="w-1 h-1 rounded-full bg-white/60"></span>
        </div>
      </div>

      <!-- Details -->
      <div class="px-1">
        <!-- Product Name -->
        <router-link :to="{ name: 'product-detail', params: { id: product.id } }" class="block text-sm sm:text-base font-black text-slate-950 leading-snug line-clamp-1 hover:text-brand-500 transition-colors mb-0.5 tracking-tight">
          {{ product.name }}
        </router-link>

        <!-- Subtitle / Category -->
        <span class="text-[11px] font-bold text-slate-600 block mb-1">
          {{ product.category?.name || 'Own the BeSmart Edition' }}
        </span>

        <!-- Description Snippet -->
        <p class="text-[11px] text-slate-700 font-medium line-clamp-2 leading-relaxed mb-3 min-h-[2.5em]">
          {{ product.description || product.short_description || 'High performance quality tech engineered for maximum efficiency and sleek aesthetic.' }}
        </p>
      </div>
    </div>

    <!-- Bottom Action Footer Div Section (Overflow-Proof) -->
    <div class="mt-auto pt-2 border-t border-slate-200/80 flex flex-col gap-2 w-full">
      <!-- Price & Discount Row -->
      <div class="flex items-center justify-between px-1">
        <span class="text-[11px] text-slate-600 font-bold">Price</span>
        <div class="flex items-baseline gap-1.5">
          <span class="text-sm sm:text-base font-black text-slate-950">৳{{ (product.sale_price || product.price).toLocaleString() }}</span>
          <span v-if="product.sale_price && product.sale_price < product.price" class="text-[10px] text-slate-500 font-semibold line-through">৳{{ product.price.toLocaleString() }}</span>
        </div>
      </div>

      <!-- Full-Width Buy Now Button in Div Container -->
      <button
        @click.prevent.stop="cartStore.addToCart(product.id, 1)"
        class="w-full py-2 sm:py-2.5 px-3 rounded-full bg-brand-500 hover:bg-brand-600 active:scale-95 text-white font-black text-xs flex items-center justify-center gap-1.5 shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer"
        id="add-to-cart-btn"
      >
        <span>Buy Now</span>
        <span class="w-4 h-4 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
          </svg>
        </span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useCartStore } from '@/stores/cart';

const props = defineProps({
  product: { type: Object, required: true }
});

const cartStore = useCartStore();
const defaultFallback = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80';

function handleImageError(e) {
  e.target.src = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80';
}

const discountPercent = computed(() => {
  if (props.product.sale_price && props.product.price > props.product.sale_price) {
    return Math.round(((props.product.price - props.product.sale_price) / props.product.price) * 100);
  }
  return 0;
});

const urgencyLabel = computed(() => {
  const stock = props.product.stock ?? ((props.product.id * 7 + 3) % 30);
  if (stock <= 0) return 'Stock Out!';
  if (stock <= 5) return `Only ${stock} left!`;
  if (stock <= 15) return 'Limited Stock!';
  if (props.product.is_flash_sale) return 'Selling Fast! 🔥';
  return '';
});

const urgencyClass = computed(() => {
  const stock = props.product.stock ?? ((props.product.id * 7 + 3) % 30);
  if (stock <= 0) return 'bg-slate-100 text-slate-500';
  if (stock <= 5) return 'bg-rose-50 text-rose-600';
  if (stock <= 15) return 'bg-amber-50 text-amber-600';
  return 'bg-amber-50 text-amber-600';
});
</script>
