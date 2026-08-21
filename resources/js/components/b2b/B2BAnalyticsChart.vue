<template>
  <div class="glass-card rounded-3xl p-6 md:p-8 border border-gray-800 bg-gray-900 text-white shadow-2xl my-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-800">
      <div>
        <span class="px-3 py-1 rounded-full bg-brand-500/20 text-brand-400 font-extrabold text-xs tracking-wider border border-brand-500/30">
          BUSINESS INTELLIGENCE
        </span>
        <h3 class="text-2xl font-extrabold text-white mt-1">10-Year Historical Purchase & Spending Trends</h3>
      </div>

      <!-- Time Interval Filters -->
      <div class="flex items-center gap-2 flex-wrap">
        <button v-for="r in ['1y', '3y', '5y', '10y']" :key="r"
          @click="$emit('change-range', r)"
          :class="[activeRange === r ? 'bg-brand-600 text-white shadow-glow' : 'bg-gray-800 text-gray-400 hover:text-white']"
          class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all">
          {{ r.toUpperCase() }}
        </button>
        <button @click="exportCSV" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md transition-colors ml-2">
          📥 Export CSV Report
        </button>
      </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 bg-gray-950/80 p-5 rounded-2xl border border-gray-800">
        <h4 class="text-sm font-bold text-gray-300 mb-4">Yearly Spending Breakdown (BDT)</h4>
        <apexchart type="bar" height="300" :options="barOptions" :series="barSeries" />
      </div>

      <div class="bg-gray-950/80 p-5 rounded-2xl border border-gray-800 flex flex-col justify-between">
        <h4 class="text-sm font-bold text-gray-300 mb-4">Category Spend Split</h4>
        <apexchart type="pie" height="280" :options="pieOptions" :series="pieSeries" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  analytics: { type: Object, required: true },
  activeRange: { type: String, default: '10y' }
});

const emit = defineEmits(['change-range']);

const barSeries = computed(() => [
  {
    name: 'Total Spending (৳)',
    data: (props.analytics?.charts?.spending_by_year || []).map(i => i.total)
  }
]);

const barOptions = computed(() => ({
  chart: { toolbar: { show: false }, background: 'transparent' },
  theme: { mode: 'dark' },
  colors: ['#0c8ee9'],
  xaxis: { categories: (props.analytics?.charts?.spending_by_year || []).map(i => i.year) },
  plotOptions: { bar: { borderRadius: 6, columnWidth: '50%' } },
  dataLabels: { enabled: false },
}));

const pieSeries = computed(() => (props.analytics?.charts?.category_breakdown || []).map(c => c.total_spent));
const pieOptions = computed(() => ({
  chart: { background: 'transparent' },
  theme: { mode: 'dark' },
  labels: (props.analytics?.charts?.category_breakdown || []).map(c => c.category),
  colors: ['#0c8ee9', '#f43f5e', '#10b981', '#f59e0b', '#8b5cf6'],
  legend: { position: 'bottom' }
}));

function exportCSV() {
  const data = props.analytics?.charts?.spending_by_year || [];
  let csv = 'Year,Total Spending (BDT),Orders Count,Savings (BDT)\n';
  data.forEach(row => {
    csv += `${row.year},${row.total},${row.order_count},${row.savings}\n`;
  });
  const blob = new Blob([csv], { type: 'text/csv' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `B2B_Analytics_Report_${props.activeRange}.csv`;
  a.click();
}
</script>
