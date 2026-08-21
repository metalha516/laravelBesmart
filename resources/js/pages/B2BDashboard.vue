<template>
  <div class="space-y-6">
    <!-- Header Summary Card -->
    <div class="glass-card rounded-3xl p-6 md:p-8 border border-gold-500/30 bg-gradient-to-r from-gray-900 via-gray-950 to-black text-white shadow-glow-gold">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/20 text-gold-400 text-xs font-black mb-2 border border-gold-500/30">
            <span>🏢 VERIFIED B2B WHOLESALER</span>
          </div>
          <h1 class="text-2xl md:text-3xl font-black">
            {{ dashboard?.business?.company_name || 'TechMart Wholesaler Ltd.' }}
          </h1>
          <p class="text-xs text-gray-400 mt-1">10-Year Business Intelligence & Wholesale Procurement Analytics</p>
        </div>

        <div class="flex items-center gap-2 bg-gray-900 p-1.5 rounded-2xl border border-gray-800 text-xs font-bold">
          <button
            v-for="r in ['1y', '3y', '5y', '10y']"
            :key="r"
            @click="changeRange(r)"
            :class="[activeRange === r ? 'bg-gold-500 text-gray-950 font-black' : 'text-gray-400 hover:text-white']"
            class="px-3 py-1.5 rounded-xl transition-colors uppercase"
          >
            {{ r }}
          </button>
        </div>
      </div>
    </div>

    <!-- Analytics Charts -->
    <B2BAnalyticsChart :analytics="dashboard?.analytics || fallbackAnalytics" />

    <!-- China Bulk Import Landed Cost Calculator Widget -->
    <ImportCostCalculator />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import B2BAnalyticsChart from '@/components/b2b/B2BAnalyticsChart.vue';
import ImportCostCalculator from '@/components/b2b/ImportCostCalculator.vue';

const dashboard = ref(null);
const activeRange = ref('10y');

const fallbackAnalytics = {
  total_spend: 14850000,
  order_count: 342,
  average_order_value: 43421,
  total_saved: 2970000,
  yearly_spending: [
    { year: 2016, total: 450000 },
    { year: 2017, total: 680000 },
    { year: 2018, total: 920000 },
    { year: 2019, total: 1150000 },
    { year: 2020, total: 1400000 },
    { year: 2021, total: 1750000 },
    { year: 2022, total: 2100000 },
    { year: 2023, total: 2450000 },
    { year: 2024, total: 2800000 },
    { year: 2025, total: 3150000 },
    { year: 2026, total: 3500000 },
  ],
  category_breakdown: [
    { category: 'Laptops', amount: 5200000 },
    { category: 'Smartphones', amount: 3800000 },
    { category: 'Audio', amount: 2400000 },
    { category: 'Wearables', amount: 1950000 },
    { category: 'Accessories', amount: 1500000 },
  ]
};

async function fetchDashboard() {
  try {
    const res = await axios.get('/api/v1/b2b/dashboard', { params: { range: activeRange.value } });
    if (res.data.success) {
      dashboard.value = res.data.data;
    } else {
      throw new Error('Fallback');
    }
  } catch (e) {
    dashboard.value = {
      business: { company_name: 'TechMart Wholesaler Ltd.' },
      analytics: fallbackAnalytics,
      recent_orders: []
    };
  }
}

function changeRange(range) {
  activeRange.value = range;
  fetchDashboard();
}

onMounted(() => {
  fetchDashboard();
});
</script>
