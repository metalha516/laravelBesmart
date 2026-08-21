<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" v-if="product">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
      <!-- Left: Image Gallery -->
      <div class="lg:col-span-6 space-y-4">
        <div class="aspect-square rounded-3xl overflow-hidden glass-card p-4 border border-gray-200 dark:border-gray-800">
          <img :src="activeImage || product.primary_image?.image_url || product.image_url || defaultFallback" @error="handleImageError" :alt="product.name" class="w-full h-full object-cover rounded-2xl" />
        </div>
        <div v-if="product.images?.length" class="flex items-center gap-3 overflow-x-auto">
          <button
            v-for="img in product.images"
            :key="img.id"
            @click="activeImage = img.image_url"
            class="w-16 h-16 rounded-xl overflow-hidden border-2 transition-all flex-shrink-0"
            :class="[activeImage === img.image_url ? 'border-brand-500 scale-105' : 'border-transparent opacity-70']"
          >
            <img :src="img.image_url" @error="handleImageError" class="w-full h-full object-cover" />
          </button>
        </div>
      </div>

      <!-- Right: Details & Tier Pricing -->
      <div class="lg:col-span-6 space-y-6">
        <div>
          <div class="flex items-center gap-2 mb-2">
            <span class="px-2.5 py-0.5 rounded-full bg-brand-500/10 text-brand-500 font-extrabold text-[10px] uppercase">
              {{ product.category?.name || 'Electronics' }}
            </span>
            <span v-if="product.is_flash_sale" class="px-2.5 py-0.5 rounded-full bg-tmall-500 text-white font-extrabold text-[10px] uppercase">
              FLASH DEAL
            </span>
          </div>
          <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white leading-tight mb-2">
            {{ product.name }}
          </h1>
          <p class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ product.sku || 'SKU-BESMART-01' }} | Verified Supplier</p>
        </div>

        <!-- Rating & Sales -->
        <div class="flex items-center gap-4 text-xs">
          <div class="flex items-center gap-1 text-gold-500 font-bold">
            <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
            <span class="text-gray-500 dark:text-gray-400 ml-1">(4.9 / 128 Reviews)</span>
          </div>
          <span class="text-gray-300">|</span>
          <span class="font-bold text-gray-700 dark:text-gray-300">12,400+ Sold</span>
        </div>

        <!-- Pricing Card -->
        <div class="glass-card rounded-2xl p-5 border border-brand-500/30 bg-gradient-to-r from-orange-50/50 to-amber-50/50 dark:from-brand-950/20 dark:to-gray-900">
          <div class="flex items-baseline justify-between">
            <span class="text-xs text-gray-500 font-bold">B2C Retail Price:</span>
            <div class="text-right">
              <span class="text-3xl font-black text-brand-500">৳{{ (product.sale_price || product.price).toLocaleString() }}</span>
              <span v-if="product.sale_price" class="text-xs text-gray-400 line-through ml-2">৳{{ product.price.toLocaleString() }}</span>
            </div>
          </div>

          <!-- B2B Wholesale Tier Pricing Matrix -->
          <div v-if="product.b2b_price || product.b2b_price_tiers?.length" class="mt-4 pt-4 border-t border-brand-500/20">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-black text-gold-600 dark:text-gold-400 flex items-center gap-1">
                <span>🏢</span> Wholesale Volume Tiers:
              </span>
              <span class="text-[10px] font-bold text-gray-400">MOQ: {{ product.moq || 1 }} Pcs</span>
            </div>
            <div class="grid grid-cols-3 gap-2 text-center text-xs">
              <div class="p-2 rounded-xl bg-white/80 dark:bg-gray-800 border border-gold-500/30">
                <div class="text-[10px] text-gray-400 font-bold">1 - 4 Pcs</div>
                <div class="font-black text-brand-500">৳{{ (product.sale_price || product.price).toLocaleString() }}</div>
              </div>
              <div class="p-2 rounded-xl bg-white/80 dark:bg-gray-800 border border-gold-500/30">
                <div class="text-[10px] text-gray-400 font-bold">5 - 19 Pcs</div>
                <div class="font-black text-gold-500">৳{{ Math.round((product.sale_price || product.price) * 0.9).toLocaleString() }}</div>
              </div>
              <div class="p-2 rounded-xl bg-white/80 dark:bg-gray-800 border border-gold-500/30">
                <div class="text-[10px] text-gray-400 font-bold">20+ Pcs</div>
                <div class="font-black text-emerald-500">৳{{ Math.round((product.sale_price || product.price) * 0.8).toLocaleString() }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quantity & Add to Cart -->
        <div class="flex items-center gap-4 pt-2">
          <div class="flex items-center rounded-2xl border border-gray-300 dark:border-gray-700 overflow-hidden bg-gray-100 dark:bg-gray-800">
            <button @click="quantity = Math.max(product.moq || 1, quantity - 1)" class="px-4 py-3 font-black text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">-</button>
            <span class="px-4 py-3 font-black text-sm text-gray-900 dark:text-white">{{ quantity }}</span>
            <button @click="quantity++" class="px-4 py-3 font-black text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">+</button>
          </div>

          <button
            @click="cartStore.addToCart(product.id, quantity)"
            class="flex-1 py-3.5 px-6 rounded-2xl bg-gradient-to-r from-brand-500 via-brand-600 to-orange-500 hover:from-brand-600 hover:to-orange-600 active:scale-95 text-white font-black text-sm uppercase tracking-wider shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2.5 cursor-pointer shadow-glow"
            id="product-detail-add-to-cart-btn"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
            </svg>
            <span>Add to Cart (৳{{ calculatedPrice.toLocaleString() }})</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import { useCartStore } from '@/stores/cart';

const route = useRoute();
const cartStore = useCartStore();

const product = ref(null);
const activeImage = ref('');
const quantity = ref(1);
const defaultFallback = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80';

function handleImageError(e) {
  e.target.src = defaultFallback;
}

const fallbackProducts = [
  {
    id: 1,
    name: 'ProGear Stealth X Pro Gaming Laptop 16"',
    price: 145000,
    sale_price: 139900,
    b2b_price: 125000,
    moq: 1,
    sku: 'LAP-PRO-16',
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=500&q=80',
    images: [
      { id: 101, image_url: 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=500&q=80' },
      { id: 102, image_url: 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=500&q=80' },
    ],
    category: { name: 'Gaming Laptops' },
    description: 'Ultra-thin 16-inch OLED gaming laptop with Intel Core i9 processor, NVIDIA RTX 4080 GPU, 32GB DDR5 RAM, and 1TB NVMe SSD.',
  },
  {
    id: 2,
    name: 'OmniSound ANC Wireless Headphones Pro',
    price: 12500,
    sale_price: 10900,
    b2b_price: 8800,
    moq: 5,
    sku: 'AUD-ANC-02',
    is_flash_sale: false,
    image_url: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80',
    images: [
      { id: 201, image_url: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80' },
      { id: 202, image_url: 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=500&q=80' },
    ],
    category: { name: 'Audio & Headphones' },
    description: 'Active Noise Canceling wireless headphones with 40-hour battery life, spatial audio processing, and dual beamforming microphones.',
  },
  {
    id: 3,
    name: 'NexusTech Ergonomic Wireless RGB Mouse',
    price: 2800,
    sale_price: 2400,
    b2b_price: 1900,
    moq: 10,
    sku: 'MSE-RGB-03',
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=500&q=80',
    images: [
      { id: 301, image_url: 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=500&q=80' },
    ],
    category: { name: 'Gaming Mice & Keyboards' },
    description: '26,000 DPI optical sensor wireless mouse with customizable RGB lighting zones, PTFE glide feet, and ultra-lightweight chassis.',
  },
  {
    id: 4,
    name: 'AeroPulse Fast Charge 100W Power Bank 25000mAh',
    price: 4500,
    sale_price: 3900,
    b2b_price: 3100,
    moq: 5,
    sku: 'PWR-25K-04',
    is_flash_sale: false,
    image_url: 'https://images.unsplash.com/photo-1609592424109-dd9892f1b177?w=500&q=80',
    images: [
      { id: 401, image_url: 'https://images.unsplash.com/photo-1609592424109-dd9892f1b177?w=500&q=80' },
    ],
    category: { name: 'Power Banks & Chargers' },
    description: 'High-capacity 25000mAh portable power bank with 100W USB-C output, digital smart screen, and fast pass-through charging.',
  },
  {
    id: 5,
    name: 'Vanguard 4K UltraHD Curved Gaming Monitor 27"',
    price: 38000,
    sale_price: 34500,
    b2b_price: 28900,
    moq: 1,
    sku: 'MON-4K-05',
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=500&q=80',
    images: [
      { id: 501, image_url: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=500&q=80' },
    ],
    category: { name: 'Monitors' },
    description: '1500R curved 4K IPS display with 165Hz refresh rate, 1ms response time, HDR400 support, and AMD FreeSync Premium Pro.',
  },
  {
    id: 6,
    name: 'NexusTech Mechanical Gaming Keyboard Red Switch',
    price: 7500,
    sale_price: 6400,
    b2b_price: 5200,
    moq: 3,
    sku: 'KBD-MECH-06',
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&q=80',
    images: [
      { id: 601, image_url: 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&q=80' },
    ],
    category: { name: 'Gaming Mice & Keyboards' },
    description: '75% gasket-mounted hot-swappable mechanical keyboard with pre-lubed linear switches, PBT double-shot keycaps, and sound dampening foam.',
  },
  {
    id: 7,
    name: 'AeroPulse 65W GaN Fast Charger Dual Port',
    price: 3200,
    sale_price: 2600,
    b2b_price: 1900,
    moq: 10,
    sku: 'CHG-GAN-07',
    is_flash_sale: false,
    image_url: 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=500&q=80',
    images: [
      { id: 701, image_url: 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=500&q=80' },
    ],
    category: { name: 'Power Banks & Chargers' },
    description: 'Compact 2-port 65W GaN fast charger compatible with laptops, tablets, smartphones, and USB-C Power Delivery devices.',
  },
  {
    id: 8,
    name: 'Vanguard Smart Security Camera 2K WiFi',
    price: 4800,
    sale_price: 3900,
    b2b_price: 3000,
    moq: 4,
    sku: 'CAM-SEC-08',
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1558002038-1055907df827?w=500&q=80',
    images: [
      { id: 801, image_url: 'https://images.unsplash.com/photo-1558002038-1055907df827?w=500&q=80' },
    ],
    category: { name: 'Security Cameras' },
    description: '2K QHD indoor smart security camera with 360-degree pan-tilt coverage, AI motion tracking, night vision, and two-way audio.',
  },
  {
    id: 9,
    name: 'ProGear HD Quadcopter Action Drone 4K',
    price: 85000,
    sale_price: 78000,
    b2b_price: 64000,
    moq: 1,
    sku: 'DRN-4K-09',
    is_flash_sale: false,
    image_url: 'https://images.unsplash.com/photo-1507582195869-42c77ec33a36?w=500&q=80',
    images: [
      { id: 901, image_url: 'https://images.unsplash.com/photo-1507582195869-42c77ec33a36?w=500&q=80' },
    ],
    category: { name: 'Cameras & Drones' },
    description: 'Professional 4K HDR camera drone with 3-axis gimbal, 35-min flight time, 10km HD video transmission, and obstacle sensing.',
  },
  {
    id: 10,
    name: 'OmniSound TWS ANC Wireless Earbuds',
    price: 6500,
    sale_price: 5400,
    b2b_price: 4100,
    moq: 5,
    sku: 'AUD-TWS-10',
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=500&q=80',
    images: [
      { id: 1001, image_url: 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=500&q=80' },
    ],
    category: { name: 'Audio & Headphones' },
    description: 'True wireless ANC earbuds with Bluetooth 5.3, IPX5 water resistance, wireless charging case, and 30-hour combined battery.',
  },
  {
    id: 11,
    name: 'Vanguard Smart Robotic Vacuum Cleaner Mop',
    price: 29500,
    sale_price: 26000,
    b2b_price: 21500,
    moq: 2,
    sku: 'VAC-ROBOT-11',
    is_flash_sale: false,
    image_url: 'https://images.unsplash.com/photo-1589630972273-c3eed6f616f7?w=500&q=80',
    images: [
      { id: 1101, image_url: 'https://images.unsplash.com/photo-1589630972273-c3eed6f616f7?w=500&q=80' },
    ],
    category: { name: 'Robotic Vacuums' },
    description: 'LiDAR navigation robot vacuum and sonic mop with 5000Pa suction power, auto-empty dust station, and app mapping controls.',
  },
  {
    id: 12,
    name: 'NexusTech 10-in-1 USB-C Docking Station',
    price: 4200,
    sale_price: 3500,
    b2b_price: 2700,
    moq: 5,
    sku: 'DCK-USBC-12',
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1468495244123-6c6c332eeece?w=500&q=80',
    images: [
      { id: 1201, image_url: 'https://images.unsplash.com/photo-1468495244123-6c6c332eeece?w=500&q=80' },
    ],
    category: { name: 'Cables & Adapters' },
    description: 'Aluminum 10-in-1 USB-C dock featuring dual 4K HDMI ports, Gigabit Ethernet, 100W PD charging, SD/TF card reader, and 3x USB 3.0.',
  },
  {
    id: 13,
    name: 'Smart Fitness Watch Series 9 GPS 49mm',
    price: 24000,
    sale_price: 19500,
    b2b_price: 16000,
    moq: 2,
    sku: 'WTC-SMART-13',
    is_flash_sale: true,
    image_url: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&q=80',
    images: [
      { id: 1301, image_url: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&q=80' },
    ],
    category: { name: 'Smart Wearables' },
    description: 'Titanium case smartwatch with Always-On Retina display, dual-frequency GPS, ECG monitor, blood oxygen sensor, and 36-hour battery.',
  },
];

function getProductById(id) {
  const found = fallbackProducts.find(p => p.id === id);
  if (found) return found;

  const categories = ['Laptops', 'Audio', 'Gaming Gear', 'Smart Wearables', 'Accessories', 'Monitors'];
  const catName = categories[(id * 3) % categories.length];
  const basePrice = 2500 + ((id * 3850) % 75000);
  const salePrice = Math.round(basePrice * 0.85);

  return {
    id: id,
    name: `BeSmart Pro Tech Item #${id} (${catName})`,
    price: basePrice,
    sale_price: salePrice,
    b2b_price: Math.round(basePrice * 0.70),
    moq: (id % 4) + 1,
    sku: `SKU-BESMART-${id.toString().padStart(2, '0')}`,
    is_flash_sale: id % 2 === 0,
    image_url: defaultFallback,
    images: [{ id: id * 10 + 1, image_url: defaultFallback }],
    category: { name: catName },
    description: `Next-generation ${catName.toLowerCase()} engineered with premium materials, smart connectivity, and 1-year official warranty.`,
  };
}

const calculatedPrice = computed(() => {
  if (!product.value) return 0;
  const unitPrice = product.value.sale_price || product.value.price;
  return unitPrice * quantity.value;
});

onMounted(async () => {
  const productId = Number(route.params.id) || 1;
  try {
    const res = await axios.get(`/api/v1/products/${productId}`);
    if (res.data.success && res.data.data?.name) {
      product.value = res.data.data;
    } else {
      throw new Error('Fallback product');
    }
  } catch (e) {
    product.value = getProductById(productId);
  }
  quantity.value = product.value.moq || 1;
  activeImage.value = product.value.primary_image?.image_url || product.value.image_url || defaultFallback;
});
</script>
