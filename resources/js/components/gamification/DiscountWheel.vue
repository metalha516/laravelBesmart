<template>
  <div class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md flex items-center justify-center p-4">
    <div class="glass-card rounded-3xl p-6 md:p-8 max-w-lg w-full text-center relative border-2 border-gold-500/60 bg-gradient-to-b from-gray-950 via-gray-900 to-black text-white shadow-[0_0_50px_rgba(255,80,0,0.5)]">
      <!-- Close Button -->
      <button @click="$emit('close')" class="absolute top-4 right-4 text-gray-400 hover:text-white font-bold text-xl w-9 h-9 rounded-full bg-gray-800/80 flex items-center justify-center border border-gray-700 transition-colors">
        ✕
      </button>

      <!-- Badge Header -->
      <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-gradient-to-r from-brand-500/20 to-gold-500/20 text-gold-400 font-black text-xs tracking-wider border border-gold-500/40 mb-2">
        <span>✨ TAOBAO GAMIFIED REWARDS</span>
      </div>
      <h2 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-gold-400 via-amber-300 to-brand-500 drop-shadow-md">
        Wheel of Fortune
      </h2>
      <p class="text-xs text-gray-300 mt-1 mb-6">Spin daily for instant discounts up to 20% OFF or Free Shipping!</p>

      <!-- Wheel Graphic Container -->
      <div class="relative w-80 h-80 mx-auto my-2 flex items-center justify-center">
        <!-- Top Pointer Triangle -->
        <div class="absolute -top-4 left-1/2 -translate-x-1/2 z-30 filter drop-shadow-[0_4px_8px_rgba(0,0,0,0.8)]">
          <div class="w-0 h-0 border-l-[14px] border-l-transparent border-r-[14px] border-r-transparent border-t-[28px] border-t-tmall-500"></div>
          <div class="w-3 h-3 rounded-full bg-gold-400 -mt-7 mx-auto shadow-md"></div>
        </div>

        <!-- Outer Glowing Rim with LED Lights -->
        <div class="absolute inset-0 rounded-full border-[10px] border-gold-500/80 shadow-[0_0_30px_rgba(255,193,7,0.6)] z-10 pointer-events-none flex items-center justify-center">
          <div v-for="i in 12" :key="i"
            class="absolute w-3 h-3 rounded-full bg-gold-300 shadow-[0_0_8px_#ffd700] animate-pulse"
            :style="{ transform: `rotate(${(i - 1) * 30}deg) translateY(-148px)` }"
          ></div>
        </div>

        <!-- SVG Wheel Pie Slices -->
        <div
          class="w-72 h-72 rounded-full overflow-hidden shadow-2xl relative transition-transform duration-[4500ms] cubic-bezier(0.15, 0.9, 0.2, 1)"
          :style="{ transform: `rotate(${rotationDegrees}deg)` }"
        >
          <svg viewBox="0 0 200 200" class="w-full h-full transform -rotate-90">
            <g v-for="(slice, index) in slices" :key="index">
              <!-- Pie Sector Path -->
              <path
                :d="getSectorPath(index, slices.length)"
                :fill="slice.color"
                stroke="#ffffff"
                stroke-width="1.5"
              />
              <!-- Sector Label Text -->
              <text
                :x="getTextX(index, slices.length)"
                :y="getTextY(index, slices.length)"
                fill="#ffffff"
                font-size="8.5"
                font-weight="900"
                text-anchor="middle"
                dominant-baseline="central"
                :transform="getTextRotation(index, slices.length)"
                class="drop-shadow-sm select-none"
              >
                {{ slice.label }}
              </text>
            </g>
          </svg>
        </div>

        <!-- Center Golden Spin Button Hub -->
        <button
          @click="spinWheel"
          :disabled="spinning"
          class="absolute z-20 w-20 h-20 rounded-full bg-gradient-to-tr from-gold-600 via-gold-400 to-amber-200 text-gray-950 font-black text-sm shadow-[0_0_20px_rgba(255,215,0,0.8)] border-4 border-white flex flex-col items-center justify-center hover:scale-105 transition-transform disabled:opacity-80 disabled:hover:scale-100 cursor-pointer"
        >
          <span class="text-xs uppercase tracking-tighter">{{ spinning ? 'SPINNING' : 'SPIN' }}</span>
          <span class="text-[10px]">👑</span>
        </button>
      </div>

      <!-- Result Banner Celebration -->
      <div v-if="result" class="mt-6 p-4 rounded-2xl bg-gradient-to-r from-brand-900/60 to-gold-900/60 border-2 border-gold-500/50 text-center animate-bounce">
        <h4 class="font-black text-gold-300 text-xl">{{ result.reward?.label }} 🎉</h4>
        <div v-if="result.reward?.coupon_code" class="mt-2 text-xs text-gray-200">
          Coupon Code:
          <span class="font-black text-amber-300 bg-black/60 px-3 py-1.5 rounded-xl border border-amber-500/40 text-sm tracking-widest ml-1 select-all">
            {{ result.reward.coupon_code }}
          </span>
        </div>
      </div>

      <!-- Footer Action -->
      <div class="mt-6">
        <button
          @click="spinWheel"
          :disabled="spinning"
          class="w-full py-4 rounded-2xl taobao-gradient-orange hover:opacity-95 text-white font-black text-sm shadow-glow transition-all disabled:opacity-50 uppercase tracking-wider cursor-pointer"
        >
          {{ spinning ? 'Spinning Wheel...' : '🎯 SPIN THE WHEEL NOW!' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useNotificationStore } from '@/stores/notification';

const emit = defineEmits(['close']);
const notify = useNotificationStore();

const spinning = ref(false);
const rotationDegrees = ref(0);
const result = ref(null);

const slices = [
  { label: '5% OFF', color: '#ff5000', code: 'BESMART5' },
  { label: '10% OFF', color: '#9333ea', code: 'BESMART10' },
  { label: '15% OFF', color: '#ff0036', code: 'BESMART15' },
  { label: '20% OFF', color: '#ffc107', code: 'BESMART20' },
  { label: 'FREE SHIP', color: '#10b981', code: 'FREESHIP' },
  { label: '৳100 OFF', color: '#0c8ee9', code: 'TAOBAO100' },
  { label: '৳200 OFF', color: '#f59e0b', code: 'TAOBAO200' },
  { label: 'NEXT TIME', color: '#475569', code: null },
];

function getSectorPath(index, total) {
  const angle = 360 / total;
  const startAngle = index * angle;
  const endAngle = (index + 1) * angle;

  const startRad = (Math.PI / 180) * startAngle;
  const endRad = (Math.PI / 180) * endAngle;

  const x1 = 100 + 95 * Math.cos(startRad);
  const y1 = 100 + 95 * Math.sin(startRad);
  const x2 = 100 + 95 * Math.cos(endRad);
  const y2 = 100 + 95 * Math.sin(endRad);

  return `M 100 100 L ${x1} ${y1} A 95 95 0 0 1 ${x2} ${y2} Z`;
}

function getTextX(index, total) {
  const angle = (index + 0.5) * (360 / total);
  const rad = (Math.PI / 180) * angle;
  return 100 + 62 * Math.cos(rad);
}

function getTextY(index, total) {
  const angle = (index + 0.5) * (360 / total);
  const rad = (Math.PI / 180) * angle;
  return 100 + 62 * Math.sin(rad);
}

function getTextRotation(index, total) {
  const angle = (index + 0.5) * (360 / total);
  const x = getTextX(index, total);
  const y = getTextY(index, total);
  return `rotate(${angle + 90}, ${x}, ${y})`;
}

async function spinWheel() {
  if (spinning.value) return;
  spinning.value = true;
  result.value = null;

  let winningIndex = 3; // Default 20% OFF for demo mode
  let rewardData = null;

  try {
    const res = await axios.post('/api/v1/wheel/spin');
    if (res.data && res.data.segment_index !== undefined) {
      winningIndex = res.data.segment_index;
      rewardData = res.data;
    }
  } catch (e) {
    // Demo Mode Fallback for GitHub Pages static preview
    winningIndex = Math.floor(Math.random() * (slices.length - 1)); // Random winning index
    const wonSlice = slices[winningIndex];
    rewardData = {
      success: true,
      reward: {
        label: wonSlice.label,
        coupon_code: wonSlice.code,
      }
    };
  }

  // Calculate rotation so top pointer hits winning sector cleanly
  const sliceAngle = 360 / slices.length;
  const targetAngle = 360 - (winningIndex * sliceAngle + sliceAngle / 2);
  rotationDegrees.value += 1800 + targetAngle - (rotationDegrees.value % 360);

  setTimeout(() => {
    spinning.value = false;
    result.value = rewardData;
    notify.show(`🎉 Congratulations! You won ${rewardData.reward?.label}`, 'success');
  }, 4500);
}
</script>
