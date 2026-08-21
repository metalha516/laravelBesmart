<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-2">Checkout</h1>
    <p class="text-xs text-gray-500 mb-8">Complete your shipping address and select payment method.</p>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <!-- Shipping & Payment Form -->
      <form @submit.prevent="submitOrder" class="lg:col-span-7 space-y-6">
        <div class="glass-card rounded-3xl p-6 border border-gray-200 dark:border-gray-800 space-y-4">
          <h3 class="font-black text-sm text-gray-900 dark:text-white uppercase tracking-wider">1. Shipping Address</h3>
          
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Full Name</label>
            <input v-model="form.shipping_address.name" type="text" required class="w-full px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs dark:text-white" />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Phone Number</label>
              <input v-model="form.shipping_address.phone" type="text" required class="w-full px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs dark:text-white" />
            </div>
            <div>
              <label class="block text-xs font-bold text-gray-500 uppercase mb-1">City</label>
              <input v-model="form.shipping_address.city" type="text" required class="w-full px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs dark:text-white" />
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Street Address</label>
            <textarea v-model="form.shipping_address.address" required rows="2" class="w-full px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs dark:text-white"></textarea>
          </div>
        </div>

        <div class="glass-card rounded-3xl p-6 border border-gray-200 dark:border-gray-800 space-y-4">
          <h3 class="font-black text-sm text-gray-900 dark:text-white uppercase tracking-wider">2. Select Payment Method</h3>
          
          <div class="grid grid-cols-2 gap-3">
            <label
              v-for="gw in gateways"
              :key="gw.id"
              :class="[form.payment_method === gw.id ? 'border-brand-500 bg-brand-500/10' : 'border-gray-200 dark:border-gray-800']"
              class="p-4 rounded-2xl border cursor-pointer flex items-center gap-3 transition-colors"
            >
              <input type="radio" v-model="form.payment_method" :value="gw.id" class="text-brand-500" />
              <div>
                <div class="font-bold text-xs text-gray-900 dark:text-white">{{ gw.name }}</div>
                <div class="text-[10px] text-gray-400">{{ gw.desc }}</div>
              </div>
            </label>
          </div>
        </div>

        <button type="submit" :disabled="loading || cartStore.items.length === 0" class="w-full py-4 rounded-2xl taobao-gradient-orange text-white font-black text-sm shadow-glow disabled:opacity-50">
          {{ loading ? 'Processing Order...' : 'Place Order Now →' }}
        </button>
      </form>

      <!-- Order Summary Sidebar -->
      <div class="lg:col-span-5">
        <div class="glass-card rounded-3xl p-6 border border-gray-200 dark:border-gray-800 space-y-4">
          <h3 class="font-black text-sm text-gray-900 dark:text-white uppercase tracking-wider">Order Summary</h3>
          
          <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
            <div v-for="item in cartStore.items" :key="item.id" class="flex items-center justify-between text-xs py-1 border-b border-gray-100 dark:border-gray-800">
              <div class="flex-1 min-w-0 pr-2">
                <div class="font-bold truncate dark:text-white">{{ item.name }}</div>
                <div class="text-[10px] text-gray-400">Qty: {{ item.quantity }} × ৳{{ item.unit_price?.toLocaleString() }}</div>
              </div>
              <div class="font-black text-brand-500">৳{{ item.subtotal?.toLocaleString() }}</div>
            </div>
          </div>

          <div class="pt-3 border-t border-gray-200 dark:border-gray-800 space-y-2 text-xs">
            <div class="flex justify-between text-gray-500">
              <span>Subtotal</span>
              <span class="font-bold text-gray-900 dark:text-white">৳{{ cartStore.subtotal.toLocaleString() }}</span>
            </div>
            <div v-if="cartStore.discount > 0" class="flex justify-between text-emerald-500">
              <span>Discount</span>
              <span class="font-bold">-৳{{ cartStore.discount.toLocaleString() }}</span>
            </div>
            <div class="flex justify-between text-gray-500">
              <span>Shipping</span>
              <span class="font-bold text-gray-900 dark:text-white">৳{{ cartStore.shipping.toLocaleString() }}</span>
            </div>
            <div class="flex justify-between text-base font-black text-brand-500 pt-2 border-t border-gray-200 dark:border-gray-800">
              <span>Total</span>
              <span>৳{{ cartStore.total.toLocaleString() }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useCartStore } from '@/stores/cart';
import { useAuthStore } from '@/stores/auth';
import { useNotificationStore } from '@/stores/notification';

const router = useRouter();
const cartStore = useCartStore();
const authStore = useAuthStore();
const notify = useNotificationStore();

const loading = ref(false);

const gateways = [
  { id: 'cod', name: 'Cash on Delivery', desc: 'Pay when delivered' },
  { id: 'stripe', name: 'Credit / Debit Card', desc: 'Instant online card' },
  { id: 'sslcommerz', name: 'SSLCommerz', desc: 'Cards, Mobile Banking' },
  { id: 'bkash', name: 'bKash / Nagad', desc: 'Direct Mobile Wallet' },
];

const form = reactive({
  shipping_address: {
    name: authStore.user?.name || 'John Customer',
    phone: '+8801700000000',
    address: 'House 12, Road 5, Block C, Banani',
    city: 'Dhaka',
  },
  payment_method: 'cod',
});

onMounted(() => {
  cartStore.fetchCart();
});

async function submitOrder() {
  if (cartStore.items.length === 0) return;
  loading.value = true;

  try {
    const res = await axios.post('/api/v1/checkout', form);
    if (res.data.success) {
      notify.show('Order placed successfully!', 'success');
      cartStore.fetchCart();
      router.push({ name: 'customer-dashboard' });
      return;
    }
  } catch (e) {
    // Client-side fallback for GitHub Pages demo mode
    const demoOrderId = 'ORD-' + Math.floor(100000 + Math.random() * 900000);
    notify.show(`Order ${demoOrderId} placed successfully!`, 'success');
    localStorage.removeItem('demo_cart_items');
    cartStore.items = [];
    cartStore.subtotal = 0;
    cartStore.discount = 0;
    cartStore.total = 0;
    router.push({ name: 'customer-dashboard' });
  } finally {
    loading.value = false;
  }
}
</script>
