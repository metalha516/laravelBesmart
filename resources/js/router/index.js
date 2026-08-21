import { createRouter, createWebHistory } from 'vue-router';
import MainLayout from '@/layouts/MainLayout.vue';
import B2BLayout from '@/layouts/B2BLayout.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import CustomerLayout from '@/layouts/CustomerLayout.vue';

import Home from '@/pages/Home.vue';
import Shop from '@/pages/Shop.vue';
import ProductDetail from '@/pages/ProductDetail.vue';
import Cart from '@/pages/Cart.vue';
import Checkout from '@/pages/Checkout.vue';
import B2BDashboard from '@/pages/B2BDashboard.vue';
import AdminDashboard from '@/pages/AdminDashboard.vue';
import CustomerDashboard from '@/pages/CustomerDashboard.vue';
import Login from '@/pages/Login.vue';
import Register from '@/pages/Register.vue';
import SaaSPricing from '@/pages/SaaSPricing.vue';

const routes = [
  {
    path: '/',
    component: MainLayout,
    children: [
      { path: '', name: 'home', component: Home },
      { path: 'shop', name: 'shop', component: Shop },
      { path: 'product/:id', name: 'product-detail', component: ProductDetail },
      { path: 'cart', name: 'cart', component: Cart },
      { path: 'checkout', name: 'checkout', component: Checkout, meta: { requiresAuth: true } },
      { path: 'login', name: 'login', component: Login },
      { path: 'register', name: 'register', component: Register },
      { path: 'saas/pricing', name: 'saas-pricing', component: SaaSPricing },
    ],
  },
  {
    path: '/b2b',
    component: B2BLayout,
    meta: { requiresAuth: true, role: 'b2b' },
    children: [
      { path: 'dashboard', name: 'b2b-dashboard', component: B2BDashboard },
    ],
  },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      { path: 'dashboard', name: 'admin-dashboard', component: AdminDashboard },
    ],
  },
  {
    path: '/customer',
    component: CustomerLayout,
    meta: { requiresAuth: true },
    children: [
      { path: 'dashboard', name: 'customer-dashboard', component: CustomerDashboard },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior() {
    return { top: 0 };
  },
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('auth_token');
  const user = JSON.parse(localStorage.getItem('user_info') || 'null');

  if (to.meta.requiresAuth && !token) {
    return next({ name: 'login' });
  }

  if (to.meta.role && user?.role !== to.meta.role && user?.role !== 'admin') {
    return next({ name: 'home' });
  }

  next();
});

export default router;
