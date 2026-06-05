/**
 * DUPL-WB-05: Pengujian algoritma fungsi kalkulasi rata-rata rating ulasan agregat
 * 
 * Menguji fungsi calculateAverageRating(ratings) yang digunakan di
 * GetProductReviewStats usecase untuk menghitung rata-rata rating produk.
 * 
 * Target: Path coverage 100%, termasuk pembuktian bahwa sistem tidak crash
 * karena pembagian dengan angka nol jika array rating kosong.
 */

const calculateAverageRating = require('../../../src/domain/helpers/calculateAverageRating');

// ── Test Suite ──────────────────────────────────────────────────────────
describe('DUPL-WB-05 – Kalkulasi Rata-rata Rating Ulasan Agregat', () => {

  // ── Path 1: Array dengan beberapa nilai [5, 4, 4, 3] → 4.0 ─────────
  test('harus mengembalikan 4.0 untuk array [5, 4, 4, 3]', () => {
    const ratings = [5, 4, 4, 3];
    const result = calculateAverageRating(ratings);

    expect(result).toBe(4.0);
    expect(typeof result).toBe('number');
  });

  // ── Path 2: Array dengan dua nilai [1, 1] → 1.0 ────────────────────
  test('harus mengembalikan 1.0 untuk array [1, 1]', () => {
    const ratings = [1, 1];
    const result = calculateAverageRating(ratings);

    expect(result).toBe(1.0);
    expect(typeof result).toBe('number');
  });

  // ── Path 3: Array kosong [] → 0 (menghindari Division by Zero) ──────
  test('harus mengembalikan 0 untuk array kosong [] (tidak crash / Division by Zero)', () => {
    const ratings = [];
    const result = calculateAverageRating(ratings);

    expect(result).toBe(0);
    expect(typeof result).toBe('number');
  });

  // ── Tambahan: null/undefined input → 0 (robustness) ─────────────────
  test('harus mengembalikan 0 jika input bukan array (null)', () => {
    const result = calculateAverageRating(null);

    expect(result).toBe(0);
  });

  test('harus mengembalikan 0 jika input bukan array (undefined)', () => {
    const result = calculateAverageRating(undefined);

    expect(result).toBe(0);
  });

  // ── Tambahan: Verifikasi pembulatan 1 desimal ───────────────────────
  test('harus membulatkan hasil ke 1 desimal (misal [5, 4, 4, 3, 2] → 3.6)', () => {
    const ratings = [5, 4, 4, 3, 2];
    const result = calculateAverageRating(ratings);

    expect(result).toBe(3.6);
  });
});
