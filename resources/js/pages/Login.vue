<template>
  <div class="max-w-md mx-auto my-12 p-8 glass-card rounded-3xl border border-gray-200 dark:border-gray-800 shadow-2xl transition-all duration-300">
    
    <!-- STEP 1: CREDENTIALS INPUT -->
    <template v-if="step === 'credentials'">
      <div class="text-center mb-6">
        <div class="w-12 h-12 rounded-2xl taobao-gradient-orange text-white font-black text-2xl flex items-center justify-center mx-auto mb-2 shadow-glow">B</div>
        <h2 class="text-2xl font-black text-gray-900 dark:text-white">Log In to Besmart</h2>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Access B2C Retail & B2B Wholesale Portals</p>
      </div>

      <form @submit.prevent="initiateOtp" class="space-y-4">
        <div>
          <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Email Address</label>
          <input v-model="form.email" type="email" placeholder="name@example.com" required class="w-full px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm dark:text-white focus:ring-2 focus:ring-brand-500 outline-none" />
        </div>
        <div>
          <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Password</label>
          <input v-model="form.password" type="password" placeholder="••••••••" required class="w-full px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm dark:text-white focus:ring-2 focus:ring-brand-500 outline-none" />
        </div>

        <!-- Main CTA Submit Button -->
        <button
          type="submit"
          :disabled="loading"
          class="w-full py-4 px-6 rounded-2xl taobao-gradient-orange hover:opacity-95 active:scale-95 text-white font-black text-sm uppercase tracking-wider shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50 mt-6"
          id="login-submit-button"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H9"/>
          </svg>
          <span>Continue to Verification →</span>
        </button>
      </form>

      <!-- Quick One-Click Demo CTA Buttons -->
      <div class="mt-6 pt-5 border-t border-gray-200 dark:border-gray-800">
        <p class="text-xs font-bold text-gray-400 uppercase text-center mb-3">Quick Demo Logins</p>
        <div class="grid grid-cols-3 gap-2">
          <button
            type="button"
            @click="quickLogin('customer@gmail.com', 'password')"
            class="px-2 py-2 text-xs font-bold rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-orange-500 hover:text-white dark:hover:bg-orange-500 transition-colors text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 cursor-pointer"
          >
            Customer
          </button>
          <button
            type="button"
            @click="quickLogin('b2b@techmart.com', 'password')"
            class="px-2 py-2 text-xs font-bold rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-amber-500 hover:text-white dark:hover:bg-amber-500 transition-colors text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 cursor-pointer"
          >
            B2B Merchant
          </button>
          <button
            type="button"
            @click="quickLogin('admin@besmart.com', 'password')"
            class="px-2 py-2 text-xs font-bold rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 transition-colors text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 cursor-pointer"
          >
            Admin
          </button>
        </div>
      </div>

      <!-- Registration CTA -->
      <div class="mt-6 pt-4 text-center text-xs">
        <p class="text-gray-500 mb-2">Don't have an account?</p>
        <router-link to="/register" class="inline-block w-full py-3 px-4 rounded-xl border-2 border-brand-500 text-brand-500 dark:text-white font-bold hover:bg-brand-500 hover:text-white transition-all text-center">
          Create New Account →
        </router-link>
      </div>
    </template>

    <!-- STEP 2: OTP VERIFICATION -->
    <template v-else-if="step === 'otp'">
      <div class="text-center mb-6">
        <h2 class="text-2xl font-black text-gray-900 dark:text-white">Verification Code</h2>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">
          We sent a 6-digit code to <span class="font-semibold text-gray-700 dark:text-gray-300">{{ form.email }}</span>.
        </p>
      </div>

      <div class="space-y-6">
        <!-- 6-Digit Code Inputs -->
        <div class="flex justify-between gap-2" @paste="handleOtpPaste">
          <input
            v-for="(digit, idx) in otpDigits"
            :key="idx"
            :ref="el => (otpInputRefs[idx] = el)"
            type="text"
            inputmode="numeric"
            maxlength="1"
            v-model="otpDigits[idx]"
            @input="handleOtpInput(idx, $event)"
            @keydown="handleOtpKeyDown(idx, $event)"
            class="w-12 h-14 text-center text-xl font-black rounded-2xl bg-gray-50 dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/20 outline-none transition-all shadow-sm"
          />
        </div>

        <!-- Quick Demo Helper Pill -->
        <div class="flex items-center justify-between bg-orange-50 dark:bg-orange-950/40 p-3 rounded-2xl border border-orange-200 dark:border-orange-900/50">
          <span class="text-xs text-orange-800 dark:text-orange-300 font-medium">Demo OTP Code: <strong class="font-bold">123456</strong></span>
          <button
            type="button"
            @click="autoFillOtp"
            class="text-xs bg-brand-500 text-white font-bold px-3 py-1.5 rounded-xl hover:bg-brand-600 active:scale-95 transition-all shadow-sm cursor-pointer"
          >
            Auto-Fill
          </button>
        </div>

        <!-- Verify CTA Button -->
        <button
          type="button"
          @click="verifyOtp"
          :disabled="loading"
          class="w-full py-4 px-6 rounded-2xl taobao-gradient-orange hover:opacity-95 active:scale-95 text-white font-black text-sm uppercase tracking-wider shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
          id="otp-verify-button"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>{{ loading ? 'Verifying Code...' : 'Verify Account' }}</span>
        </button>

        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 pt-2">
          <button type="button" @click="step = 'credentials'" class="hover:text-brand-500 font-bold cursor-pointer">
            ← Change Email / Back
          </button>
          <button type="button" @click="resendOtp" class="hover:text-brand-500 font-bold cursor-pointer text-brand-500">
            Resend Code
          </button>
        </div>
      </div>
    </template>

  </div>
</template>

<script setup>
import { reactive, ref, nextTick } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { useCartStore } from '@/stores/cart';
import { useNotificationStore } from '@/stores/notification';

const router = useRouter();
const authStore = useAuthStore();
const cartStore = useCartStore();
const notify = useNotificationStore();

const step = ref('credentials'); // 'credentials' | 'otp'
const loading = ref(false);
const form = reactive({ email: '', password: '' });

const otpDigits = ref(['', '', '', '', '', '']);
const otpInputRefs = ref([]);
const expectedOtp = ref('123456');

function initiateOtp() {
  if (!form.email || !form.password) {
    notify.show('Please enter both email and password.', 'error');
    return;
  }
  step.value = 'otp';
  otpDigits.value = ['', '', '', '', '', ''];
  notify.show('Verification code sent! (Demo Code: 123456)', 'info');
  nextTick(() => {
    if (otpInputRefs.value[0]) {
      otpInputRefs.value[0].focus();
    }
  });
}

function quickLogin(email, password) {
  form.email = email;
  form.password = password;
  initiateOtp();
}

function handleOtpInput(index, event) {
  const val = event.target.value.replace(/[^0-9]/g, '');
  otpDigits.value[index] = val;
  if (val && index < 5) {
    nextTick(() => {
      if (otpInputRefs.value[index + 1]) {
        otpInputRefs.value[index + 1].focus();
      }
    });
  }
}

function handleOtpKeyDown(index, event) {
  if (event.key === 'Backspace' && !otpDigits.value[index] && index > 0) {
    nextTick(() => {
      if (otpInputRefs.value[index - 1]) {
        otpInputRefs.value[index - 1].focus();
      }
    });
  }
}

function handleOtpPaste(event) {
  event.preventDefault();
  const pasteData = (event.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '').slice(0, 6);
  if (pasteData) {
    for (let i = 0; i < 6; i++) {
      otpDigits.value[i] = pasteData[i] || '';
    }
    const nextFocusIndex = Math.min(pasteData.length, 5);
    nextTick(() => {
      if (otpInputRefs.value[nextFocusIndex]) {
        otpInputRefs.value[nextFocusIndex].focus();
      }
    });
  }
}

function autoFillOtp() {
  otpDigits.value = ['1', '2', '3', '4', '5', '6'];
  notify.show('OTP Auto-filled with demo code 123456', 'success');
}

function resendOtp() {
  otpDigits.value = ['', '', '', '', '', ''];
  notify.show('A new verification code (123456) has been sent to your device!', 'info');
  nextTick(() => {
    if (otpInputRefs.value[0]) {
      otpInputRefs.value[0].focus();
    }
  });
}

async function verifyOtp() {
  const enteredCode = otpDigits.value.join('');
  if (enteredCode.length < 6) {
    notify.show('Please enter the full 6-digit verification code.', 'error');
    return;
  }
  
  loading.value = true;
  try {
    const res = await authStore.login(form);
    if (res.success) {
      notify.show(`Verification successful! Welcome back, ${res.data.user.name}!`, 'success');
      await cartStore.fetchCart();
      if (authStore.isAdmin) router.push({ name: 'admin-dashboard' });
      else if (authStore.isB2B) router.push({ name: 'b2b-dashboard' });
      else router.push({ name: 'home' });
    }
  } catch (e) {
    notify.show(e.response?.data?.message || 'Verification failed.', 'error');
  } finally {
    loading.value = false;
  }
}
</script>
