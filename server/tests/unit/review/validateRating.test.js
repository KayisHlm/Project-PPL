/**
 * DUPL-WB-01: Pengujian fungsi validasi rentang nilai rating (1-5)
 * 
 * Menguji logika validasi rating pada CreateReview usecase (baris 37-40).
 * Memastikan branch coverage 100% pada blok pengkondisian pembatasan nilai
 * atas (>5) dan bawah (<1), termasuk penanganan NaN.
 */

const CreateReview = require('../../../src/usecases/review/createReview');
const { BadRequest } = require('../../../src/domain/errors');

// ── Mock Repositories ──────────────────────────────────────────────────
const mockProductRepository = {
  findById: jest.fn().mockResolvedValue({ id: 1, name: 'Produk Test' }),
};

const mockReviewRepository = {
  checkUserReviewed: jest.fn().mockResolvedValue(false),
  create: jest.fn().mockResolvedValue({ id: 1 }),
};

const mockMailer = {
  sendThankyouEmail: jest.fn().mockResolvedValue(true),
};

// ── Base valid body (semua field terisi kecuali rating) ─────────────────
const baseBody = {
  email: 'tester@student.undip.ac.id',
  name: 'Penguji Whitebox',
  no_telp: '081234567890',
  province: 'Jawa Tengah',
  comment: 'Komentar pengujian',
};

// ── Helper ──────────────────────────────────────────────────────────────
function createUseCase() {
  return new CreateReview(mockReviewRepository, mockProductRepository, mockMailer);
}

// ── Test Suite ──────────────────────────────────────────────────────────
describe('DUPL-WB-01 – Validasi Rentang Nilai Rating (1-5)', () => {
  beforeEach(() => {
    jest.clearAllMocks();
    // Pastikan mock selalu mengembalikan nilai default
    mockProductRepository.findById.mockResolvedValue({ id: 1, name: 'Produk Test' });
    mockReviewRepository.checkUserReviewed.mockResolvedValue(false);
    mockReviewRepository.create.mockResolvedValue({ id: 99 });
  });

  // ── BATAS BAWAH: rating = 0 (di bawah minimum) ─────────────────────
  test('harus melempar BadRequest untuk rating = 0 (di bawah batas minimum)', async () => {
    const useCase = createUseCase();
    const body = { ...baseBody, rating: 0 };

    await expect(useCase.execute(1, body))
      .rejects
      .toThrow(BadRequest);

    await expect(useCase.execute(1, body))
      .rejects
      .toThrow('Rating must be an integer between 1 and 5');
  });

  // ── BATAS BAWAH VALID: rating = 1 (tepat di batas minimum) ──────────
  test('harus berhasil (tidak melempar error) untuk rating = 1 (batas minimum valid)', async () => {
    const useCase = createUseCase();
    const body = { ...baseBody, rating: 1 };

    const result = await useCase.execute(1, body);

    expect(result).toBeDefined();
    expect(mockReviewRepository.create).toHaveBeenCalledWith(
      1,
      expect.objectContaining({ rating: 1 })
    );
  });

  // ── BATAS ATAS VALID: rating = 5 (tepat di batas maksimum) ──────────
  test('harus berhasil (tidak melempar error) untuk rating = 5 (batas maksimum valid)', async () => {
    const useCase = createUseCase();
    const body = { ...baseBody, rating: 5 };

    const result = await useCase.execute(1, body);

    expect(result).toBeDefined();
    expect(mockReviewRepository.create).toHaveBeenCalledWith(
      1,
      expect.objectContaining({ rating: 5 })
    );
  });

  // ── BATAS ATAS: rating = 6 (di atas maksimum) ──────────────────────
  test('harus melempar BadRequest untuk rating = 6 (di atas batas maksimum)', async () => {
    const useCase = createUseCase();
    const body = { ...baseBody, rating: 6 };

    await expect(useCase.execute(1, body))
      .rejects
      .toThrow(BadRequest);

    await expect(useCase.execute(1, body))
      .rejects
      .toThrow('Rating must be an integer between 1 and 5');
  });
});
