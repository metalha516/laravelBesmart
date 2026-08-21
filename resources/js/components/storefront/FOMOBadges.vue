<template>
  <div v-if="fomo" class="space-y-2 my-4">
    <!-- Stock Urgency -->
    <div v-if="fomo.stock_urgency" class="flex items-center gap-2 p-3 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-400 text-xs font-bold animate-pulse">
      <span>🔥</span>
      <span>{{ fomo.stock_urgency }}</span>
    </div>

    <!-- Active Viewers -->
    <div v-if="fomo.recent_viewers" class="flex items-center gap-2 p-2.5 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-600 dark:text-blue-400 text-xs font-medium">
      <span>👀</span>
      <span>{{ fomo.recent_viewers }}</span>
    </div>

    <!-- Recent Purchases -->
    <div v-if="fomo.recent_purchases" class="flex items-center gap-2 p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs font-medium">
      <span>🛍️</span>
      <span>{{ fomo.recent_purchases }}</span>
    </div>

    <!-- Flash Sale Countdown -->
    <div v-if="fomo.flash_sale_ending" class="flex items-center gap-2 p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-extrabold">
      <span>⏳</span>
      <span>{{ fomo.flash_sale_ending }}</span>
    </div>
  </div>
</template>

<script setup>
defineProps({
  fomo: { type: Object, required: true }
});
</script>
