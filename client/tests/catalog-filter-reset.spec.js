import { test, expect } from '@playwright/test';

test.describe('Catalog filter reset', () => {
  test('test_catalog_filter_reset_returns_default_product_list', async ({ page }) => {
    await page.goto('/');

    const productCards = page.locator('#store-grid .col[data-index]');
    const visibleCards = page.locator('#store-grid .col[data-index]:visible');
    const initialCount = await visibleCards.count();
    if (initialCount === 0) {
      const emptyAlert = page.locator('.alert:has-text("Belum ada produk")');
      await expect(emptyAlert).toBeVisible();
      test.skip(true, 'Catalog kosong, tidak ada produk untuk diuji.');
    }

    await expect(visibleCards.first()).toBeVisible();

    const firstCard = visibleCards.first();
    const productName = (await firstCard.getAttribute('data-name')) || '';
    const productCategory = (await firstCard.getAttribute('data-category')) || '';

    const productInput = page.locator('#store-search-product');
    await expect(productInput).toBeVisible();
    await productInput.fill(productName);

    const categorySelect = page.locator('#store-filter-category');
    if ((await categorySelect.count()) > 0 && productCategory) {
      await categorySelect.selectOption({ label: productCategory });
    }

    await expect(visibleCards.first()).toBeVisible();
    const filteredCount = await visibleCards.count();
    expect(filteredCount).toBeGreaterThan(0);
    expect(filteredCount).toBeLessThanOrEqual(initialCount);

    const resetButton = page.locator('#store-filter-reset');
    await expect(resetButton).toBeVisible();
    await resetButton.click();

    await expect(visibleCards.first()).toBeVisible();
    const resetCount = await visibleCards.count();
    expect(resetCount).toBe(initialCount);
  });
});
