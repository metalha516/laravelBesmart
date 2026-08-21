<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row gap-8">
      <!-- Sidebar Filters -->
      <aside class="w-full md:w-64 flex-shrink-0 space-y-6">
        <div class="glass-card rounded-3xl p-5 border border-gray-200 dark:border-gray-800">
          <h3 class="font-black text-sm text-gray-900 dark:text-white uppercase tracking-wider mb-4">Categories</h3>
          <ul class="space-y-2 text-xs">
            <li>
              <button
                @click="selectedCategory = null; fetchProducts()"
                :class="[!selectedCategory ? 'text-brand-500 font-black' : 'text-gray-600 dark:text-gray-400 font-bold']"
                class="hover:text-brand-500 transition-colors"
              >
                All Products
              </button>
            </li>
            <li v-for="cat in categories" :key="cat.id">
              <button
                @click="selectedCategory = cat.id; fetchProducts()"
                :class="[selectedCategory === cat.id ? 'text-brand-500 font-black' : 'text-gray-600 dark:text-gray-400 font-bold']"
                class="hover:text-brand-500 transition-colors"
              >
                {{ cat.name }}
              </button>
            </li>
          </ul>
        </div>

        <div class="glass-card rounded-3xl p-5 border border-gray-200 dark:border-gray-800">
          <h3 class="font-black text-sm text-gray-900 dark:text-white uppercase tracking-wider mb-4">Price Range (৳)</h3>
          <div class="space-y-3">
            <div class="flex items-center gap-2">
              <input v-model.number="minPrice" type="number" placeholder="Min" class="w-full px-3 py-1.5 rounded-xl bg-gray-100 dark:bg-gray-800 text-xs border border-gray-300 dark:border-gray-700" />
              <span class="text-xs font-bold">-</span>
              <input v-model.number="maxPrice" type="number" placeholder="Max" class="w-full px-3 py-1.5 rounded-xl bg-gray-100 dark:bg-gray-800 text-xs border border-gray-300 dark:border-gray-700" />
            </div>
            <button @click="fetchProducts" class="w-full py-2 rounded-xl taobao-gradient-orange text-white text-xs font-black shadow-glow">
              Filter Price
            </button>
          </div>
        </div>
      </aside>

      <!-- Main Product Grid -->
      <main class="flex-1">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-200 dark:border-gray-800">
          <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">Shop Catalog</h1>
            <p class="text-xs text-gray-500 mt-1">Found {{ products.length }} items</p>
          </div>

          <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-500">Sort By:</span>
            <select v-model="selectedSort" @change="fetchProducts" class="px-3 py-1.5 rounded-xl bg-gray-100 dark:bg-gray-800 text-xs font-bold border border-gray-300 dark:border-gray-700 dark:text-white">
              <option value="featured">Featured</option>
              <option value="price_low">Price: Low to High</option>
              <option value="price_high">Price: High to Low</option>
            </select>
          </div>
        </div>

        <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 animate-pulse">
          <div v-for="i in 8" :key="i" class="h-64 rounded-2xl bg-gray-200 dark:bg-gray-800"></div>
        </div>

        <div v-else-if="products.length === 0" class="text-center py-16 glass-card rounded-3xl">
          <span class="text-4xl">🔍</span>
          <h3 class="font-black text-lg text-gray-900 dark:text-white mt-2">No products found</h3>
          <p class="text-xs text-gray-500 mt-1">Try adjusting your filters or search terms.</p>
        </div>

        <div v-else class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 pb-16 md:pb-0">
          <ProductCard v-for="product in products" :key="product.id" :product="product" />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import ProductCard from '@/components/storefront/ProductCard.vue';

const route = useRoute();

const products = ref([]);
const categories = ref([]);
const selectedCategory = ref(null);
const selectedSort = ref('featured');
const minPrice = ref(null);
const maxPrice = ref(null);
const loading = ref(true);

const fallbackCategories = [
  { id: 1, name: 'Laptops & Computers' },
  { id: 2, name: 'Smartphones & Accessories' },
  { id: 3, name: 'Audio & Headphones' },
  { id: 4, name: 'Smart Wearables' },
  { id: 5, name: 'Gaming Gear & Components' },
];

const fallbackProducts = [
  {
    id: 1,
    name: 'ProGear Stealth X Pro Gaming Laptop 16"',
    price: 145000,
    sale_price: 139900,
    b2b_price: 125000,
    moq: 1,
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=500&q=80',
    category: { id: 1, name: 'Gaming Laptops' },
  },
  {
    id: 2,
    name: 'OmniSound ANC Wireless Headphones Pro',
    price: 12500,
    sale_price: 10900,
    b2b_price: 8800,
    moq: 5,
    is_flash_sale: false,
    image_url: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80',
    category: { id: 3, name: 'Audio & Headphones' },
  },
  {
    id: 3,
    name: 'NexusTech Ergonomic Wireless RGB Mouse',
    price: 2800,
    sale_price: 2400,
    b2b_price: 1900,
    moq: 10,
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=500&q=80',
    category: { id: 7, name: 'Gaming Mice & Keyboards' },
  },
  {
    id: 4,
    name: 'AeroPulse Fast Charge 100W Power Bank 25000mAh',
    price: 4500,
    sale_price: 3900,
    b2b_price: 3100,
    moq: 5,
    is_flash_sale: false,
    image_url: 'https://images.unsplash.com/photo-1609592424109-dd9892f1b177?w=500&q=80',
    category: { id: 8, name: 'Power Banks & Chargers' },
  },
  {
    id: 5,
    name: 'Vanguard 4K UltraHD Curved Gaming Monitor 27"',
    price: 38000,
    sale_price: 34500,
    b2b_price: 28900,
    moq: 1,
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=500&q=80',
    category: { id: 6, name: 'Monitors' },
  },
  {
    id: 6,
    name: 'NexusTech Mechanical Gaming Keyboard Red Switch',
    price: 7500,
    sale_price: 6400,
    b2b_price: 5200,
    moq: 3,
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&q=80',
    category: { id: 7, name: 'Gaming Mice & Keyboards' },
  },
  {
    id: 7,
    name: 'AeroPulse 65W GaN Fast Charger Dual Port',
    price: 3200,
    sale_price: 2600,
    b2b_price: 1900,
    moq: 10,
    is_flash_sale: false,
    image_url: 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=500&q=80',
    category: { id: 8, name: 'Power Banks & Chargers' },
  },
  {
    id: 8,
    name: 'Vanguard Smart Security Camera 2K WiFi',
    price: 4800,
    sale_price: 3900,
    b2b_price: 3000,
    moq: 4,
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1558002038-1055907df827?w=500&q=80',
    category: { id: 11, name: 'Security Cameras' },
  },
];

async function fetchProducts() {
  loading.value = true;
  try {
    const params = {
      category_id: selectedCategory.value,
      sort: selectedSort.value,
      min_price: minPrice.value,
      max_price: maxPrice.value,
      search: route.query.search,
    };
    const res = await axios.get('/api/v1/products', { params });
    const items = res.data.data?.data || res.data.data;
    products.value = items?.length ? items : fallbackProducts;
  } catch (e) {
    products.value = fallbackProducts;
  } finally {
    loading.value = false;
  }
}

function handleImageError(e) {
  e.target.src = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80';
}

onMounted(async () => {
  if (route.query.category_id) {
    selectedCategory.value = Number(route.query.category_id);
  }
  try {
    const catRes = await axios.get('/api/v1/categories');
    categories.value = catRes.data.data?.length ? catRes.data.data : fallbackCategories;
  } catch (e) {
    categories.value = fallbackCategories;
  }
  fetchProducts();
});

watch(() => route.query, () => {
  if (route.query.category_id) {
    selectedCategory.value = Number(route.query.category_id);
  }
  fetchProducts();
});
</script>
