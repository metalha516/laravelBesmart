<template>
  <div class="max-w-md mx-auto my-12 p-8 glass-card rounded-3xl border border-gray-200 dark:border-gray-800 shadow-2xl">
    <div class="text-center mb-6">
      <h2 class="text-2xl font-black text-gray-900 dark:text-white">Create Besmart Account</h2>
      <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Select B2C Customer or B2B Wholesale Retailer</p>
    </div>

    <form @submit.prevent="handleRegister" class="space-y-4">
      <div>
        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Account Role</label>
        <div class="grid grid-cols-2 gap-2">
          <button type="button" @click="form.role = 'b2c'" :class="[form.role === 'b2c' ? 'bg-brand-600 text-white font-bold' : 'bg-gray-100 dark:bg-gray-800 text-gray-400']" class="py-2.5 rounded-xl text-xs">
            B2C Customer
          </button>
          <button type="button" @click="form.role = 'b2b'" :class="[form.role === 'b2b' ? 'bg-amber-500 text-gray-950 font-bold' : 'bg-gray-100 dark:bg-gray-800 text-gray-400']" class="py-2.5 rounded-xl text-xs">
            B2B Retailer
          </button>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Full Name</label>
        <input v-model="form.name" type="text" required class="w-full px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm dark:text-white" />
      </div>

      <div>
        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Email Address</label>
        <input v-model="form.email" type="email" required class="w-full px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm dark:text-white" />
      </div>

      <div>
        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Password</label>
        <input v-model="form.password" type="password" required class="w-full px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm dark:text-white" />
      </div>

      <template v-if="form.role === 'b2b'">
        <div>
          <label class="block text-xs font-bold text-amber-400 uppercase mb-1">Company / Store Name</label>
          <input v-model="form.company_name" type="text" required class="w-full px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 border border-amber-500/40 text-sm dark:text-white" />
        </div>
        <div>
          <label class="block text-xs font-bold text-amber-400 uppercase mb-1">Trade License Number</label>
          <input v-model="form.trade_license" type="text" class="w-full px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 border border-amber-500/40 text-sm dark:text-white" />
        </div>
      </template>

      <button
        type="submit"
        :disabled="loading"
        class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-brand-500 via-brand-600 to-orange-500 hover:from-brand-600 hover:to-orange-600 active:scale-95 text-white font-black text-sm uppercase tracking-wider shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer shadow-glow disabled:opacity-50 mt-6"
        id="register-submit-button"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
        </svg>
        <span>{{ loading ? 'Registering Account...' : 'Complete Registration →' }}</span>
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { useNotificationStore } from '@/stores/notification';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();
const notify = useNotificationStore();

const loading = ref(false);
const form = reactive({
  name: '',
  email: '',
  password: '',
  role: 'b2c',
  company_name: '',
  trade_license: '',
});

onMounted(() => {
  if (route.query.role === 'b2b') {
    form.role = 'b2b';
  }
});

async function handleRegister() {
  loading.value = true;
  try {
    const res = await authStore.register(form);
    if (res.success) {
      notify.show('Account created successfully!', 'success');
      if (form.role === 'b2b') router.push({ name: 'b2b-dashboard' });
      else router.push({ name: 'home' });
    }
  } catch (e) {
    notify.show(e.response?.data?.message || 'Error registering account.', 'error');
  } finally {
    loading.value = false;
  }
}
</script>
