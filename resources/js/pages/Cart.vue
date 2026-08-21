<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8">Shopping Cart</h1>

    <div v-if="cartStore.items.length === 0" class="glass-card rounded-3xl p-12 text-center text-gray-400">
      <p class="text-lg font-bold mb-4">Your cart is currently empty.</p>
      <router-link to="/shop" class="px-6 py-3 bg-brand-600 text-white rounded-xl text-xs font-bold shadow-glow">
        Start Shopping Now →
      </router-link>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Items List -->
      <div class="lg:col-span-2 space-y-4">
        <div v-for="item in cartStore.items" :key="item.id" class="glass-card rounded-2xl p-4 flex items-center gap-4">
          <img :src="item.image_url || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80'" @error="handleImageError" class="w-20 h-20 rounded-xl object-cover" />
          <div class="flex-1 min-w-0">
            <h4 class="font-bold text-sm text-gray-900 dark:text-white truncate">{{ item.name }}</h4>
            <p class="text-xs text-gray-400">Unit Price: ৳{{ item.unit_price.toLocaleString() }}</p>
            <div class="flex items-center gap-3 mt-2">
              <input
                type="number"
                :min="item.moq || 1"
                v-model.number="item.quantity"
                @change="cartStore.updateQuantity(item.id, item.quantity)"
                class="w-20 px-2 py-1 rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs font-bold text-center dark:text-white"
              />
              <button @click="cartStore.removeItem(item.id)" class="text-xs font-semibold text-rose-500 hover:underline">
                Remove
              </button>
            </div>
          </div>
          <div class="text-right">
            <span class="font-extrabold text-base text-gray-900 dark:text-white">৳{{ item.subtotal.toLocaleString() }}</span>
          </div>
        </div>
      </div>

      <!-- Summary -->
      <div class="glass-card rounded-3xl p-6 h-fit space-y-4 border border-gray-200 dark:border-gray-800">
        <h3 class="font-bold text-base text-gray-900 dark:text-white">Order Summary</h3>

        <!-- Coupon Code Form -->
        <div class="flex gap-2">
          <input v-model="couponCode" type="text" placeholder="Coupon Code" class="flex-1 px-3 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs dark:text-white" />
          <button @click="cartStore.applyCoupon(couponCode)" class="px-4 py-2 bg-gray-900 dark:bg-gray-700 text-white rounded-xl text-xs font-bold">
            Apply
          </button>
        </div>

        <div class="space-y-2 text-xs text-gray-600 dark:text-gray-400 pt-4 border-t border-gray-200 dark:border-gray-800">
          <div class="flex justify-between"><span>Subtotal:</span><span class="font-bold text-gray-900 dark:text-white">৳{{ cartStore.subtotal.toLocaleString() }}</span></div>
          <div v-if="cartStore.discount > 0" class="flex justify-between text-emerald-500 font-bold"><span>Discount:</span><span>-৳{{ cartStore.discount.toLocaleString() }}</span></div>
          <div class="flex justify-between"><span>Shipping:</span><span class="font-bold text-gray-900 dark:text-white">৳{{ cartStore.shipping.toLocaleString() }}</span></div>
          <div class="flex justify-between text-base font-extrabold text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-gray-800">
            <span>Total:</span>
            <span class="text-brand-500">৳{{ cartStore.total.toLocaleString() }}</span>
          </div>
        </div>

        <router-link to="/checkout" class="block w-full py-3.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-xs text-center shadow-glow">
          Proceed to Checkout →
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useCartStore } from '@/stores/cart';

const cartStore = useCartStore();
const couponCode = ref('');

function handleImageError(e) {
  e.target.src = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80';
}

onMounted(() => {
  cartStore.fetchCart();
});
</script>
