<template>
  <div v-if="bundle" class="glass-card rounded-3xl p-6 md:p-8 border border-brand-500/30 my-8 shadow-xl bg-gradient-to-r from-brand-950/20 to-blue-950/20">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-800">
      <div>
        <span class="px-2.5 py-1 rounded-full bg-brand-500/20 text-brand-400 font-extrabold text-xs uppercase tracking-wider">
          SMART BUNDLE SAVINGS
        </span>
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">Complete Your Setup & Save {{ bundle.discount_percentage }}%</h3>
      </div>
      <div class="text-right">
        <span class="text-xs text-gray-400 block">Bundle Price:</span>
        <span class="text-2xl font-extrabold text-brand-500">৳{{ bundle.bundle_price.toLocaleString() }}</span>
        <span class="text-xs text-emerald-400 font-bold block">Save ৳{{ bundle.savings.toLocaleString() }}</span>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
      <!-- Primary Product -->
      <div class="flex items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl border border-gray-200 dark:border-gray-800">
        <img :src="bundle.main_product.image_url || 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=300&q=80'" class="w-16 h-16 rounded-xl object-cover" />
        <div>
          <span class="text-[10px] font-bold text-brand-500 uppercase">Primary Product</span>
          <h4 class="font-bold text-xs text-gray-900 dark:text-white line-clamp-1">{{ bundle.main_product.name }}</h4>
          <span class="font-extrabold text-sm">৳{{ bundle.main_product.price.toLocaleString() }}</span>
        </div>
      </div>

      <!-- Plus Items -->
      <div v-for="item in bundle.bundle_products" :key="item.id" class="flex items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl border border-gray-200 dark:border-gray-800">
        <img :src="item.image_url || 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=300&q=80'" class="w-16 h-16 rounded-xl object-cover" />
        <div>
          <span class="text-[10px] font-bold text-emerald-500 uppercase">+ Complementary Item</span>
          <h4 class="font-bold text-xs text-gray-900 dark:text-white line-clamp-1">{{ item.name }}</h4>
          <span class="font-extrabold text-sm">৳{{ item.price.toLocaleString() }}</span>
        </div>
      </div>
    </div>

    <div class="mt-6 flex justify-end">
      <button @click="buyBundle" class="px-8 py-3 rounded-2xl bg-gradient-to-r from-brand-600 to-blue-600 hover:from-brand-700 hover:to-blue-700 text-white font-extrabold text-sm shadow-glow transition-all">
        🛒 Buy Complete Bundle (Save ৳{{ bundle.savings.toLocaleString() }})
      </button>
    </div>
  </div>
</template>

<script setup>
import { useCartStore } from '@/stores/cart';
import { useNotificationStore } from '@/stores/notification';

const props = defineProps({
  bundle: { type: Object, required: true }
});

const cartStore = useCartStore();
const notify = useNotificationStore();

async function buyBundle() {
  await cartStore.addToCart(props.bundle.main_product.id, 1);
  for (const p of props.bundle.bundle_products) {
    await cartStore.addToCart(p.id, 1);
  }
  notify.show('Complete Setup Bundle added to your cart!', 'success');
}
</script>
