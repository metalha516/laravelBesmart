<template>
  <div class="glass-card rounded-3xl p-6 md:p-8 border border-gold-500/40 bg-gradient-to-br from-gray-900 via-gray-950 to-black text-white">
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-800">
      <div class="w-10 h-10 rounded-2xl bg-gold-500/20 text-gold-400 font-bold text-xl flex items-center justify-center border border-gold-500/30">
        🧮
      </div>
      <div>
        <h2 class="text-xl font-black text-white">China Landed Cost Import Calculator</h2>
        <p class="text-xs text-gray-400">Compute FOB, Air/Sea Freight, Customs Duty (15%), VAT (15%), & Break-even Volume</p>
      </div>
    </div>

    <form @submit.prevent="runCalculation" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div>
        <label class="block text-[10px] font-bold text-gold-400 uppercase mb-1">Product Name</label>
        <input v-model="form.product_name" type="text" required class="w-full px-3.5 py-2 rounded-xl bg-gray-900 border border-gray-700 text-xs text-white focus:outline-none focus:border-gold-500" />
      </div>

      <div>
        <label class="block text-[10px] font-bold text-gold-400 uppercase mb-1">FOB Unit Price ($ USD)</label>
        <input v-model.number="form.unit_price" type="number" step="0.01" min="0.1" required class="w-full px-3.5 py-2 rounded-xl bg-gray-900 border border-gray-700 text-xs text-white focus:outline-none focus:border-gold-500" />
      </div>

      <div>
        <label class="block text-[10px] font-bold text-gold-400 uppercase mb-1">Order Quantity (Units)</label>
        <input v-model.number="form.quantity" type="number" min="1" required class="w-full px-3.5 py-2 rounded-xl bg-gray-900 border border-gray-700 text-xs text-white focus:outline-none focus:border-gold-500" />
      </div>

      <div>
        <label class="block text-[10px] font-bold text-gold-400 uppercase mb-1">Unit Weight (kg)</label>
        <input v-model.number="form.weight_kg" type="number" step="0.01" min="0.01" required class="w-full px-3.5 py-2 rounded-xl bg-gray-900 border border-gray-700 text-xs text-white focus:outline-none focus:border-gold-500" />
      </div>

      <div>
        <label class="block text-[10px] font-bold text-gold-400 uppercase mb-1">Shipping Method</label>
        <select v-model="form.shipping_method" class="w-full px-3.5 py-2 rounded-xl bg-gray-900 border border-gray-700 text-xs text-white focus:outline-none focus:border-gold-500">
          <option value="air">✈️ Air Cargo ($8.50/kg)</option>
          <option value="sea">🚢 Sea Freight ($2.50/kg)</option>
        </select>
      </div>

      <div class="lg:col-span-3 flex items-end">
        <button type="submit" :disabled="loading" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-gold-500 to-amber-500 text-gray-950 font-black text-xs shadow-glow-gold hover:opacity-95 transition-opacity disabled:opacity-50">
          {{ loading ? 'Calculating Costs...' : '⚡ Calculate Total Landed Cost' }}
        </button>
      </div>
    </form>

    <!-- Calculation Results Breakdown -->
    <div v-if="calculation" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-5 rounded-2xl bg-gray-900/90 border border-gold-500/30 text-xs">
      <div class="p-3 rounded-xl bg-black/50 border border-gray-800">
        <span class="text-[10px] text-gray-400 uppercase font-bold">Total Investment</span>
        <div class="text-xl font-black text-gold-400 mt-1">৳{{ calculation.summary?.total_investment_bdt?.toLocaleString() }}</div>
      </div>
      <div class="p-3 rounded-xl bg-black/50 border border-gray-800">
        <span class="text-[10px] text-gray-400 uppercase font-bold">Landed Cost / Unit</span>
        <div class="text-xl font-black text-brand-500 mt-1">৳{{ calculation.summary?.cost_per_unit_bdt?.toLocaleString() }}</div>
      </div>
      <div class="p-3 rounded-xl bg-black/50 border border-gray-800">
        <span class="text-[10px] text-gray-400 uppercase font-bold">Target Retail Price</span>
        <div class="text-xl font-black text-emerald-400 mt-1">৳{{ calculation.summary?.suggested_selling_price_bdt?.toLocaleString() }}</div>
      </div>
      <div class="p-3 rounded-xl bg-black/50 border border-gray-800">
        <span class="text-[10px] text-gray-400 uppercase font-bold">Expected Net Profit</span>
        <div class="text-xl font-black text-purple-400 mt-1">৳{{ calculation.summary?.expected_profit_bdt?.toLocaleString() }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import axios from 'axios';

const loading = ref(false);
const calculation = ref(null);

const form = reactive({
  product_name: 'Wireless Ergonomic RGB Mice',
  unit_price: 15,
  quantity: 200,
  weight_kg: 0.25,
  shipping_method: 'air',
  customs_duty_rate: 15,
  vat_rate: 15,
  desired_margin_rate: 35,
});

async function runCalculation() {
  loading.value = true;
  try {
    const res = await axios.post('/api/v1/b2b/calculate-import', form);
    if (res.data.success) {
      calculation.value = res.data.data;
      return;
    }
  } catch (e) {
    // Client-side math calculation fallback for GitHub Pages static preview
    const productPriceUsd = form.unit_price || 15;
    const qty = form.quantity || 200;
    const weightKg = form.weight_kg || 0.25;
    const isAir = form.shipping_method === 'air';

    const exchangeRate = 120; // 1 USD = 120 BDT
    const fobBdt = productPriceUsd * qty * exchangeRate;
    const totalWeight = weightKg * qty;
    const shippingRatePerKg = isAir ? 8.5 : 2.5;
    const shippingCostBdt = totalWeight * shippingRatePerKg * exchangeRate;
    const customsDutyBdt = fobBdt * 0.15;
    const vatBdt = (fobBdt + customsDutyBdt + shippingCostBdt) * 0.15;

    const totalLandedCostBdt = fobBdt + shippingCostBdt + customsDutyBdt + vatBdt;
    const landedCostPerUnit = totalLandedCostBdt / qty;
    const targetRetailPrice = landedCostPerUnit * 1.35;
    const expectedProfit = (targetRetailPrice - landedCostPerUnit) * qty;

    calculation.value = {
      summary: {
        total_investment_bdt: Math.round(totalLandedCostBdt),
        cost_per_unit_bdt: Math.round(landedCostPerUnit),
        suggested_selling_price_bdt: Math.round(targetRetailPrice),
        expected_profit_bdt: Math.round(expectedProfit),
        breakeven_volume: Math.ceil(totalLandedCostBdt / targetRetailPrice),
      },
      breakdown: {
        fob_cost_bdt: Math.round(fobBdt),
        shipping_cost_bdt: Math.round(shippingCostBdt),
        customs_duty_bdt: Math.round(customsDutyBdt),
        vat_bdt: Math.round(vatBdt),
      }
    };
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  runCalculation();
});
</script>
