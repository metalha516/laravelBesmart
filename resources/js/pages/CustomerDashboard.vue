<template>
  <div class="space-y-6">
    <div class="glass-card rounded-3xl p-6 border border-brand-500/30 bg-gradient-to-r from-gray-900 via-gray-950 to-black text-white">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl taobao-gradient-orange text-white font-black text-2xl flex items-center justify-center shadow-glow">
          {{ authStore.user?.name?.charAt(0) || '👤' }}
        </div>
        <div>
          <span class="px-3 py-1 rounded-full bg-brand-500/20 text-brand-400 text-xs font-black border border-brand-500/30">
            CUSTOMER PROFILE
          </span>
          <h1 class="text-2xl font-black mt-1">{{ authStore.user?.name || 'John Customer' }}</h1>
          <p class="text-xs text-gray-400">{{ authStore.user?.email || 'customer@gmail.com' }}</p>
        </div>
      </div>
    </div>

    <!-- Order History Table -->
    <div class="glass-card rounded-3xl p-6 border border-gray-200 dark:border-gray-800">
      <h3 class="font-black text-sm text-gray-900 dark:text-white uppercase tracking-wider mb-4">My Orders & Status</h3>
      
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="border-b border-gray-200 dark:border-gray-800 text-gray-400 uppercase font-bold">
              <th class="pb-3">Order #</th>
              <th class="pb-3">Date</th>
              <th class="pb-3">Items</th>
              <th class="pb-3">Total</th>
              <th class="pb-3">Payment</th>
              <th class="pb-3">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
              <td class="py-3 font-bold text-brand-500">#{{ order.order_number }}</td>
              <td class="py-3 text-gray-500">{{ order.created_at }}</td>
              <td class="py-3 dark:text-white">{{ order.item_count }} Items</td>
              <td class="py-3 font-black text-gray-900 dark:text-white">৳{{ order.total_amount?.toLocaleString() }}</td>
              <td class="py-3 uppercase text-[10px] font-extrabold text-gray-500">{{ order.payment_method }}</td>
              <td class="py-3">
                <span :class="[order.status === 'delivered' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500']" class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase">
                  {{ order.status }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
const orders = ref([]);

const fallbackOrders = [
  { id: 1, order_number: 'ORD-842910', created_at: '2026-08-12', item_count: 2, total_amount: 178900, payment_method: 'COD', status: 'processing' },
  { id: 2, order_number: 'ORD-719302', created_at: '2026-08-01', item_count: 1, total_amount: 9900, payment_method: 'bKash', status: 'delivered' },
  { id: 3, order_number: 'ORD-548102', created_at: '2026-07-20', item_count: 3, total_amount: 32400, payment_method: 'Stripe', status: 'delivered' },
];

onMounted(async () => {
  try {
    const res = await axios.get('/api/v1/auth/me');
    if (res.data.success && res.data.data.orders) {
      orders.value = res.data.data.orders;
    } else {
      orders.value = fallbackOrders;
    }
  } catch (e) {
    orders.value = fallbackOrders;
  }
});
</script>
