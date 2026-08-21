<template>
  <div class="fixed bottom-6 right-6 z-40">
    <!-- Floating AI Drawer Button -->
    <button
      @click="isOpen = !isOpen"
      class="w-14 h-14 rounded-full taobao-gradient-orange text-white flex items-center justify-center shadow-glow hover:scale-110 transition-transform relative border-2 border-white"
    >
      <span class="text-2xl">🤖</span>
      <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white animate-pulse"></span>
    </button>

    <!-- AI Chat Window -->
    <div
      v-if="isOpen"
      class="absolute bottom-16 right-0 w-80 sm:w-96 glass-card rounded-3xl border border-gray-200 dark:border-gray-800 shadow-2xl overflow-hidden flex flex-col h-[500px] bg-white dark:bg-gray-900"
    >
      <!-- Header -->
      <div class="taobao-gradient-orange text-white p-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-2xl bg-white/20 flex items-center justify-center text-xl font-bold">
            🤖
          </div>
          <div>
            <h4 class="font-black text-sm">Besmart AI Assistant</h4>
            <p class="text-[10px] text-orange-100 font-bold">Product Finder & Assistant</p>
          </div>
        </div>
        <button @click="isOpen = false" class="text-white hover:opacity-80 font-bold text-lg">✕</button>
      </div>

      <!-- Messages Stream -->
      <div class="flex-1 p-4 overflow-y-auto space-y-3 text-xs">
        <div v-for="(msg, i) in messages" :key="i" :class="[msg.role === 'user' ? 'text-right' : 'text-left']">
          <div
            :class="[
              msg.role === 'user'
                ? 'bg-brand-500 text-white inline-block rounded-2xl rounded-tr-none px-3.5 py-2 max-w-[85%]'
                : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 inline-block rounded-2xl rounded-tl-none px-3.5 py-2 max-w-[90%] border border-gray-200 dark:border-gray-700'
            ]"
          >
            <p class="leading-relaxed">{{ msg.text }}</p>

            <!-- Product Recommendations inside Chat -->
            <div v-if="msg.products && msg.products.length" class="mt-3 space-y-2">
              <div v-for="p in msg.products" :key="p.id" class="bg-white dark:bg-gray-900 p-2.5 rounded-xl border border-gray-200 dark:border-gray-700 flex items-center gap-3 text-left">
                <img :src="p.image_url" class="w-12 h-12 rounded-lg object-cover" />
                <div class="flex-1 min-w-0">
                  <h5 class="font-bold text-[11px] truncate text-gray-900 dark:text-white">{{ p.name }}</h5>
                  <div class="flex items-center justify-between mt-1">
                    <span class="font-black text-brand-500 text-xs">৳{{ p.price?.toLocaleString() }}</span>
                    <button @click="cartStore.addToCart(p.id, 1)" class="px-2.5 py-1 bg-gradient-to-r from-brand-500 to-orange-500 hover:from-brand-600 hover:to-orange-600 text-white text-[10px] font-extrabold rounded-lg shadow-sm hover:shadow active:scale-95 transition-all cursor-pointer">
                      + Add to Cart
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-if="loading" class="text-left">
          <div class="bg-gray-100 dark:bg-gray-800 text-gray-500 inline-block rounded-2xl px-3 py-2 text-xs animate-pulse">
            Besmart AI is searching catalog...
          </div>
        </div>
      </div>

      <!-- Quick Suggestion Pills -->
      <div class="p-2 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-800 flex items-center gap-1.5 overflow-x-auto text-[10px]">
        <button @click="query = 'Gaming laptops under 200000'; sendMessage()" class="px-2.5 py-1 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold whitespace-nowrap hover:bg-brand-500 hover:text-white">
          💻 Gaming Laptops
        </button>
        <button @click="query = 'ANC Headphones'; sendMessage()" class="px-2.5 py-1 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold whitespace-nowrap hover:bg-brand-500 hover:text-white">
          🎧 Headphones
        </button>
      </div>

      <!-- Input Field -->
      <div class="p-3 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 flex items-center gap-2">
        <input
          v-model="query"
          @keyup.enter="sendMessage"
          type="text"
          placeholder="Ask AI e.g. 'gaming mouse under 3000'..."
          class="flex-1 px-3 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs dark:text-white focus:outline-none"
        />
        <button @click="sendMessage" class="px-4 py-2 taobao-gradient-orange text-white font-bold rounded-xl text-xs">
          Send
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useCartStore } from '@/stores/cart';

const cartStore = useCartStore();
const isOpen = ref(false);
const query = ref('');
const loading = ref(false);

const messages = ref([
  {
    role: 'assistant',
    text: 'Hello! I am your Besmart AI Assistant. What product or setup are you looking for today?',
  }
]);

const fallbackProducts = [
  { id: 1, name: 'Pro Ultra Gaming Laptop 16" OLED', price: 169000, image_url: 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=500&q=80' },
  { id: 2, name: 'Wireless ANC Noise-Canceling Headphones', price: 9900, image_url: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80' },
  { id: 3, name: 'Precision RGB Ergonomic Wireless Mouse', price: 3200, image_url: 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=500&q=80' },
];

async function sendMessage() {
  if (!query.value.trim() || loading.value) return;
  const userText = query.value.trim();
  query.value = '';

  messages.value.push({ role: 'user', text: userText });
  loading.value = true;

  try {
    const res = await axios.post('/api/v1/ai/chat', { prompt: userText });
    if (res.data.success) {
      messages.value.push({
        role: 'assistant',
        text: res.data.data.reply,
        products: res.data.data.products || [],
      });
    } else {
      throw new Error('API fallback');
    }
  } catch (e) {
    // Static fallback for GitHub Pages demo preview
    messages.value.push({
      role: 'assistant',
      text: `Based on your request "${userText}", here are top recommended products from our catalog:`,
      products: fallbackProducts,
    });
  } finally {
    loading.value = false;
  }
}
</script>
