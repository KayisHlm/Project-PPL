import { test, expect } from '@playwright/test';

const PRODUCT_CARD_SELECTOR = '#store-grid .col[data-index]';
const SEARCH_INPUT_SELECTOR = '[data-testid="search-product"], input[placeholder*="Cari"], input[placeholder*="produk"], input[type="search"]';
const RESET_BUTTON_SELECTOR = '[data-testid="reset-filter"], button:has-text("Reset")';
const DETAIL_BUTTON_SELECTOR = 'a:has-text("Detail")';
const LOGIN_NAV_SELECTOR = '[data-testid="login-link"], a:has-text("Login"), button:has-text("Login")';
const LOGIN_SUBMIT_SELECTOR = '[data-testid="login-submit"], button:has-text("Login")';

test.describe('Automated Testing MartPlace - 5 Butir Uji', () => {
  test('DUPL-04-01 - Menampilkan katalog tanpa login', async ({ page }) => {
    await page.goto('/');

    await expect(page).toHaveURL(/.*/);

    const productCards = page.locator(PRODUCT_CARD_SELECTOR);

    await expect(productCards.first()).toBeVisible();

    const productCount = await productCards.count();
    expect(productCount).toBeGreaterThan(0);
  });

  test('DUPL-04-10 - Mencari produk berdasarkan nama', async ({ page }) => {
    await page.goto('/');

    const searchInput = page.locator(SEARCH_INPUT_SELECTOR).first();
    await expect(searchInput).toBeVisible();

    await searchInput.fill('Macbook');

    await page.keyboard.press('Enter');

    await page.waitForTimeout(1000);

    const productCards = page.locator(PRODUCT_CARD_SELECTOR);
    const visibleCards = page.locator(`${PRODUCT_CARD_SELECTOR}:visible`);
    await expect(visibleCards.first()).toBeVisible();

    const firstName = await visibleCards.first().getAttribute('data-name');
    expect((firstName || '').toLowerCase()).toContain('macbook');
  });

  test('DUPL-04-11 - Mencari produk berdasarkan kategori', async ({ page }) => {
    await page.goto('/');

    const categoryDropdown = page.locator('#store-filter-category');
    await expect(categoryDropdown).toBeVisible();

    // Pilih kategori 'Electronics'
    await categoryDropdown.selectOption('Electronics');

    await page.waitForTimeout(1000);

    // Ambil semua produk yang tampil setelah di-filter
    const visibleCards = page.locator(`${PRODUCT_CARD_SELECTOR}:visible`);
    await expect(visibleCards.first()).toBeVisible();

    // Pastikan semua produk yang tampil memiliki data-category="Electronics"
    const count = await visibleCards.count();
    for (let i = 0; i < count; i++) {
      const categoryAttr = await visibleCards.nth(i).getAttribute('data-category');
      expect(categoryAttr).toBe('Electronics');
    }
  });

  test('DUPL-04-78 - Melakukan reset filter atau pencarian melalui reset button', async ({ page }) => {
    await page.goto('/');

    const searchInput = page.locator(SEARCH_INPUT_SELECTOR).first();
    await expect(searchInput).toBeVisible();

    await searchInput.fill('Macbook');
    await page.keyboard.press('Enter');

    await page.waitForTimeout(1000);

    const resetButton = page.locator(RESET_BUTTON_SELECTOR).first();
    await expect(resetButton).toBeVisible();

    await resetButton.click();

    await page.waitForTimeout(1000);

    await expect(searchInput).toHaveValue('');

    const productCards = page.locator(PRODUCT_CARD_SELECTOR);
    await expect(productCards.first()).toBeVisible();

    const productCount = await productCards.count();
    expect(productCount).toBeGreaterThan(0);
  });

  test('DUPL-05-01 - Menampilkan halaman detail produk tanpa login', async ({ page }) => {
    await page.goto('/');

    const productCards = page.locator(PRODUCT_CARD_SELECTOR);
    await expect(productCards.first()).toBeVisible();

    const firstProduct = productCards.first();
    const detailButton = firstProduct.locator(DETAIL_BUTTON_SELECTOR).first();

    await expect(detailButton).toBeVisible();

    await detailButton.click();

    await page.waitForTimeout(1000);

    await expect(page).toHaveURL(/detail|produk|product/i);

    const bodyText = await page.textContent('body');

    expect(bodyText.length).toBeGreaterThan(0);
    expect(bodyText.toLowerCase()).toMatch(/harga|stok|berat|kategori|deskripsi|rating|ulasan|toko/);
  });

  test('DUPL-05-03 - Menampilkan nama produk', async ({ page }) => {
    await page.goto('/');

    const productCards = page.locator(PRODUCT_CARD_SELECTOR);
    await expect(productCards.first()).toBeVisible();

    const firstProduct = productCards.first();
    
    // Ambil nama produk dari halaman katalog
    const productTitleLocator = firstProduct.locator('h6.mb-1.text-truncate').first();
    await expect(productTitleLocator).toBeVisible();
    const expectedName = (await productTitleLocator.innerText()).trim();

    const detailButton = firstProduct.locator(DETAIL_BUTTON_SELECTOR).first();
    await expect(detailButton).toBeVisible();

    await detailButton.click();

    await page.waitForTimeout(1000);

    // Pastikan berada di halaman detail produk
    await expect(page).toHaveURL(/detail|produk|product/i);

    // Pastikan nama produk di halaman detail tampil dengan jelas dan sama persis
    const detailTitleLocator = page.locator('h4.fw-bold.mb-1').first();
    await expect(detailTitleLocator).toBeVisible();
    
    const actualName = (await detailTitleLocator.innerText()).trim();
    expect(actualName).toBe(expectedName);
  });

  test('DUPL-15-02 - Mengosongkan field email dan password lalu menekan tombol Login', async ({ page }) => {
    await page.goto('/');

    const loginNav = page.locator(LOGIN_NAV_SELECTOR).first();
    await expect(loginNav).toBeVisible();

    await loginNav.click();

    await page.waitForTimeout(1000);

    const loginButton = page.locator(LOGIN_SUBMIT_SELECTOR).last();
    await expect(loginButton).toBeVisible();

    await loginButton.click();

    await page.waitForTimeout(1000);

    const bodyText = await page.textContent('body');

    expect(bodyText.toLowerCase()).toMatch(/email|password|wajib|required|harus diisi|tidak boleh kosong/);
  });
});
