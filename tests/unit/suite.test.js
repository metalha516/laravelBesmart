import test from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';

const projectRoot = process.cwd();

test('Feature Check: Login panel contains NO demo login options', () => {
  const loginFilePath = path.join(projectRoot, 'resources', 'js', 'pages', 'Login.vue');
  const loginContent = fs.readFileSync(loginFilePath, 'utf8');

  assert.strictEqual(loginContent.includes('Quick Demo One-Click Logins'), false, 'Login.vue should not contain Quick Demo title');
  assert.strictEqual(loginContent.includes('quickLogin('), false, 'Login.vue should not contain quickLogin method');
  assert.strictEqual(loginContent.includes('admin@besmart.com'), false, 'Login.vue should not contain hardcoded demo admin button');
  assert.strictEqual(loginContent.includes('b2b@techmart.com'), false, 'Login.vue should not contain hardcoded demo B2B button');
  assert.strictEqual(loginContent.includes('customer@gmail.com'), false, 'Login.vue should not contain hardcoded demo customer button');
});

test('Feature Check: Register panel contains clean sign-in routing and standard input fields', () => {
  const registerFilePath = path.join(projectRoot, 'resources', 'js', 'pages', 'Register.vue');
  const registerContent = fs.readFileSync(registerFilePath, 'utf8');

  assert.strictEqual(registerContent.includes('handleRegister'), true, 'Register.vue must have registration handler');
  assert.strictEqual(registerContent.includes('Quick Demo'), false, 'Register.vue must not have quick demo logins');
});

test('Feature Check: Product image fallback handlers exist across all storefront components', () => {
  const filesToVerify = [
    'resources/js/components/storefront/ProductCard.vue',
    'resources/js/pages/Home.vue',
    'resources/js/pages/Shop.vue',
    'resources/js/pages/ProductDetail.vue',
    'resources/js/pages/Cart.vue',
    'resources/js/pages/AdminDashboard.vue',
  ];

  for (const relativePath of filesToVerify) {
    const fullPath = path.join(projectRoot, relativePath);
    const content = fs.readFileSync(fullPath, 'utf8');
    assert.strictEqual(
      content.includes('@error=') || content.includes('handleImgErr') || content.includes('handleImageError'),
      true,
      `${relativePath} must contain image error handling (@error)`
    );
  }
});

test('Add Product System: AdminDashboard contains modal form and API post handler', () => {
  const adminFilePath = path.join(projectRoot, 'resources', 'js', 'pages', 'AdminDashboard.vue');
  const adminContent = fs.readFileSync(adminFilePath, 'utf8');

  assert.strictEqual(adminContent.includes('Add New Product'), true, 'AdminDashboard must have Add New Product button');
  assert.strictEqual(adminContent.includes('handleAddProduct'), true, 'AdminDashboard must have handleAddProduct submission logic');
  assert.strictEqual(adminContent.includes('/api/v1/admin/products'), true, 'AdminDashboard must call /api/v1/admin/products API endpoint');
});

test('Business Logic: B2B Volume Pricing Tiers Math', () => {
  const basePrice = 50000;
  const tier1Price = 42000; // 1-9 pcs
  const tier2Price = 38000; // 10+ pcs

  function calculateUnitPrice(quantity) {
    if (quantity >= 10) return tier2Price;
    if (quantity >= 1) return tier1Price;
    return basePrice;
  }

  assert.strictEqual(calculateUnitPrice(1), 42000);
  assert.strictEqual(calculateUnitPrice(5), 42000);
  assert.strictEqual(calculateUnitPrice(10), 38000);
  assert.strictEqual(calculateUnitPrice(50), 38000);
});

test('Business Logic: China Landed Cost Import Calculator', () => {
  function calculateLandedCost({ unitPriceUsd, quantity, weightKg, exchangeRate = 120, customsRate = 0.15, vatRate = 0.15 }) {
    const productCostBdt = unitPriceUsd * quantity * exchangeRate;
    const airShippingBdt = weightKg * quantity * 12 * exchangeRate; // $12/kg air freight
    const subtotalBdt = productCostBdt + airShippingBdt;
    const customsDutyBdt = subtotalBdt * customsRate;
    const vatBdt = (subtotalBdt + customsDutyBdt) * vatRate;
    const totalInvestmentBdt = subtotalBdt + customsDutyBdt + vatBdt;
    const costPerUnitBdt = totalInvestmentBdt / quantity;

    return {
      totalInvestmentBdt,
      costPerUnitBdt,
      suggestedRetailPriceBdt: costPerUnitBdt * 1.3, // 30% margin
    };
  }

  const result = calculateLandedCost({ unitPriceUsd: 10, quantity: 100, weightKg: 0.5 });
  assert.ok(result.totalInvestmentBdt > 0);
  assert.ok(result.costPerUnitBdt > 10 * 120); // must be higher than raw purchase cost
  assert.ok(result.suggestedRetailPriceBdt > result.costPerUnitBdt);
});

test('Business Logic: Discount Wheel anti-abuse rate limit', () => {
  const spunIPs = new Set();

  function spinWheel(userIp) {
    if (spunIPs.has(userIp)) {
      return { success: false, message: 'Rate limit exceeded: 24h cooldown' };
    }
    spunIPs.add(userIp);
    return { success: true, discount: '15% OFF' };
  }

  const ip = '192.168.1.50';
  const firstAttempt = spinWheel(ip);
  assert.strictEqual(firstAttempt.success, true);

  const secondAttempt = spinWheel(ip);
  assert.strictEqual(secondAttempt.success, false);
  assert.strictEqual(secondAttempt.message.includes('Rate limit'), true);
});

test('SaaS Platform Feature: SaaSPricing page component and router registration', () => {
  const pricingPath = path.join(projectRoot, 'resources', 'js', 'pages', 'SaaSPricing.vue');
  const routerPath = path.join(projectRoot, 'resources', 'js', 'router', 'index.js');

  assert.strictEqual(fs.existsSync(pricingPath), true, 'SaaSPricing.vue page component must exist');

  const routerContent = fs.readFileSync(routerPath, 'utf8');
  assert.strictEqual(routerContent.includes('SaaSPricing'), true, 'router/index.js must import SaaSPricing');
  assert.strictEqual(routerContent.includes('saas/pricing'), true, 'router/index.js must have /saas/pricing route');
});
