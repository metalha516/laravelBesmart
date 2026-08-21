<template>
  <footer class="besmart-footer">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8">
        <!-- Brand Column -->
        <div class="lg:col-span-2">
          <div class="flex items-center gap-2 mb-4">
            <div class="w-9 h-9 rounded-lg bg-brand-500 text-white font-extrabold text-lg flex items-center justify-center">B</div>
            <span class="font-extrabold text-xl text-white">BeSmart</span>
          </div>
          <p class="text-sm text-gray-400 mb-4 leading-relaxed max-w-sm">
            Bangladesh's trusted online marketplace. Shop smart with competitive prices,
            authentic products, secure payment, and fast delivery.
          </p>
          <!-- Social Links -->
          <div class="besmart-footer__socials">
            <a href="#" class="besmart-footer__social-link" aria-label="Facebook">📘</a>
            <a href="#" class="besmart-footer__social-link" aria-label="Instagram">📸</a>
            <a href="#" class="besmart-footer__social-link" aria-label="YouTube">▶️</a>
            <a href="#" class="besmart-footer__social-link" aria-label="Twitter">🐦</a>
          </div>
        </div>

        <!-- Customer Care -->
        <div>
          <h4>Customer Care</h4>
          <ul>
            <li><a href="#">Help Center</a></li>
            <li><a href="#">How to Buy</a></li>
            <li><a href="#">Returns & Refunds</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="#">Delivery Info</a></li>
          </ul>
        </div>

        <!-- About BeSmart -->
        <div>
          <h4>About BeSmart</h4>
          <ul>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Careers</a></li>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Service</a></li>
            <li><router-link to="/register?role=b2b">Sell on BeSmart</router-link></li>
          </ul>
        </div>

        <!-- Earn with BeSmart -->
        <div>
          <h4>Earn with BeSmart</h4>
          <ul>
            <li><router-link to="/register?role=b2b">Become a Seller</router-link></li>
            <li><a href="#">Affiliate Program</a></li>
            <li><router-link to="/b2b/dashboard">B2B Portal</router-link></li>
            <li><a href="#">Advertise</a></li>
          </ul>

          <!-- Payment Methods -->
          <h4 class="mt-6">Payment Methods</h4>
          <div class="besmart-footer__payments">
            <span class="besmart-footer__payment-icon">bKash</span>
            <span class="besmart-footer__payment-icon">COD</span>
            <span class="besmart-footer__payment-icon">VISA</span>
            <span class="besmart-footer__payment-icon">MC</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="besmart-footer__bottom">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p>© 2026 BeSmart E-Commerce Marketplace. All rights reserved. Secure Payments by SSLCommerz, bKash, Visa, Mastercard.</p>
        <p class="mt-1 text-gray-500">📍 House 42, Road 11, Block D, Banani, Dhaka-1213 | 📞 +880 1700 000 001 | ✉️ support@besmart.com</p>
      </div>
    </div>
  </footer>
</template>
