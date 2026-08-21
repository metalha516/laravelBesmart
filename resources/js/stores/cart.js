import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import axios from 'axios';
import { useNotificationStore } from './notification';

const fallbackProducts = [
  { id: 1, name: 'ProGear Stealth X Pro Gaming Laptop 16"', price: 139900, image_url: 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=500&q=80', moq: 1 },
  { id: 2, name: 'OmniSound ANC Wireless Headphones Pro', price: 10900, image_url: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80', moq: 5 },
  { id: 3, name: 'NexusTech Ergonomic Wireless RGB Mouse', price: 2400, image_url: 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=500&q=80', moq: 10 },
  { id: 4, name: 'AeroPulse Fast Charge 100W Power Bank 25000mAh', price: 3900, image_url: 'https://images.unsplash.com/photo-1609592424109-dd9892f1b177?w=500&q=80', moq: 5 },
  { id: 5, name: 'Vanguard 4K UltraHD Curved Gaming Monitor 27"', price: 34500, image_url: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=500&q=80', moq: 1 },
  { id: 6, name: 'NexusTech Mechanical Gaming Keyboard Red Switch', price: 6400, image_url: 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&q=80', moq: 3 },
  { id: 7, name: 'AeroPulse 65W GaN Fast Charger Dual Port', price: 2600, image_url: 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=500&q=80', moq: 10 },
  { id: 8, name: 'Vanguard Smart Security Camera 2K WiFi', price: 3900, image_url: 'https://images.unsplash.com/photo-1558002038-1055907df827?w=500&q=80', moq: 4 },
  { id: 9, name: 'ProGear HD Quadcopter Action Drone 4K', price: 78000, image_url: 'https://images.unsplash.com/photo-1507582195869-42c77ec33a36?w=500&q=80', moq: 1 },
  { id: 10, name: 'OmniSound TWS ANC Wireless Earbuds', price: 5400, image_url: 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=500&q=80', moq: 5 },
  { id: 11, name: 'Vanguard Smart Robotic Vacuum Cleaner Mop', price: 26000, image_url: 'https://images.unsplash.com/photo-1589630972273-c3eed6f616f7?w=500&q=80', moq: 2 },
  { id: 12, name: 'NexusTech 10-in-1 USB-C Docking Station', price: 3500, image_url: 'https://images.unsplash.com/photo-1468495244123-6c6c332eeece?w=500&q=80', moq: 5 },
  { id: 13, name: 'Smart Fitness Watch Series 9 GPS 49mm', price: 19500, image_url: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&q=80', moq: 2 },
];

export const useCartStore = defineStore('cart', () => {
  const items = ref(JSON.parse(localStorage.getItem('demo_cart_items') || '[]'));
  const subtotal = ref(0);
  const discount = ref(0);
  const shipping = ref(0);
  const total = ref(0);
  const couponCode = ref(localStorage.getItem('demo_coupon_code') || '');
  const isOpen = ref(false);

  function recalculateLocal() {
    subtotal.value = items.value.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
    if (couponCode.value === 'BESMART20') {
      discount.value = Math.round(subtotal.value * 0.20);
    } else if (couponCode.value === 'BESMART10') {
      discount.value = Math.round(subtotal.value * 0.10);
    } else {
      discount.value = 0;
    }
    shipping.value = subtotal.value > 2000 || subtotal.value === 0 ? 0 : 120;
    total.value = Math.max(0, subtotal.value - discount.value + shipping.value);
    localStorage.setItem('demo_cart_items', JSON.stringify(items.value));
    localStorage.setItem('demo_coupon_code', couponCode.value);
  }

  recalculateLocal();

  const itemCount = computed(() => items.value.reduce((acc, i) => acc + i.quantity, 0));

  async function fetchCart() {
    try {
      const res = await axios.get('/api/v1/cart');
      if (res.data.success) {
        const d = res.data.data;
        items.value = d.items;
        subtotal.value = d.subtotal;
        discount.value = d.discount;
        shipping.value = d.shipping;
        total.value = d.total;
        couponCode.value = d.coupon_code || '';
        return;
      }
    } catch (e) {
      recalculateLocal();
    }
  }

  async function addToCart(productId, quantity = 1) {
    const notify = useNotificationStore();
    try {
      const res = await axios.post('/api/v1/cart/items', { product_id: productId, quantity });
      if (res.data.success) {
        const d = res.data.data;
        items.value = d.items;
        subtotal.value = d.subtotal;
        discount.value = d.discount;
        shipping.value = d.shipping;
        total.value = d.total;
        isOpen.value = true;
        notify.show('Item added to your cart!', 'success');
        return;
      }
    } catch (e) {
      // Local fallback for GitHub Pages demo mode
      const foundProduct = fallbackProducts.find(p => p.id === productId) || {
        id: productId,
        name: `Product #${productId}`,
        price: 5000,
        image_url: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80'
      };

      const existingIndex = items.value.findIndex(i => i.product_id === productId);
      if (existingIndex > -1) {
        items.value[existingIndex].quantity += quantity;
        items.value[existingIndex].subtotal = items.value[existingIndex].quantity * items.value[existingIndex].unit_price;
      } else {
        items.value.push({
          id: Date.now(),
          product_id: productId,
          name: foundProduct.name,
          image_url: foundProduct.image_url,
          unit_price: foundProduct.price,
          quantity: quantity,
          subtotal: foundProduct.price * quantity,
        });
      }
      recalculateLocal();
      isOpen.value = true;
      notify.show('Item added to your cart!', 'success');
    }
  }

  async function updateQuantity(itemId, quantity) {
    if (quantity < 1) return removeItem(itemId);
    try {
      const res = await axios.put(`/api/v1/cart/items/${itemId}`, { quantity });
      if (res.data.success) {
        const d = res.data.data;
        items.value = d.items;
        subtotal.value = d.subtotal;
        discount.value = d.discount;
        shipping.value = d.shipping;
        total.value = d.total;
        return;
      }
    } catch (e) {
      const idx = items.value.findIndex(i => i.id === itemId);
      if (idx > -1) {
        items.value[idx].quantity = quantity;
        items.value[idx].subtotal = items.value[idx].unit_price * quantity;
        recalculateLocal();
      }
    }
  }

  async function removeItem(itemId) {
    const notify = useNotificationStore();
    try {
      const res = await axios.delete(`/api/v1/cart/items/${itemId}`);
      if (res.data.success) {
        const d = res.data.data;
        items.value = d.items;
        subtotal.value = d.subtotal;
        discount.value = d.discount;
        shipping.value = d.shipping;
        total.value = d.total;
        notify.show('Item removed', 'info');
        return;
      }
    } catch (e) {
      items.value = items.value.filter(i => i.id !== itemId);
      recalculateLocal();
      notify.show('Item removed', 'info');
    }
  }

  async function applyCoupon(code) {
    const notify = useNotificationStore();
    try {
      const res = await axios.post('/api/v1/cart/coupon', { code });
      if (res.data.success) {
        const d = res.data.data;
        items.value = d.items;
        subtotal.value = d.subtotal;
        discount.value = d.discount;
        shipping.value = d.shipping;
        total.value = d.total;
        couponCode.value = d.coupon_code;
        notify.show(`Coupon ${code} applied successfully!`, 'success');
        return;
      }
    } catch (e) {
      couponCode.value = code.toUpperCase();
      recalculateLocal();
      notify.show(`Coupon ${code} applied!`, 'success');
    }
  }

  return {
    items, subtotal, discount, shipping, total, couponCode, isOpen, itemCount,
    fetchCart, addToCart, updateQuantity, removeItem, applyCoupon
  };
});
