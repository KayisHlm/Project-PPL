const ReviewInformation = require('../../../src/dto/review/reviewInformation');

describe('ReviewInformation DTO', () => {
  test('should map review with camelCase properties', () => {
    const review = {
      id: 1,
      productId: 10,
      email: 'test@example.com',
      name: 'John Doe',
      noTelp: '08123456789',
      rating: 5,
      comment: 'Excellent product!',
      createdAt: '2024-01-01T00:00:00Z',
      updatedAt: '2024-01-02T00:00:00Z'
    };

    const dto = new ReviewInformation(review);

    expect(dto.id).toBe(1);
    expect(dto.productId).toBe(10);
    expect(dto.email).toBe('test@example.com');
    expect(dto.name).toBe('John Doe');
    expect(dto.noTelp).toBe('08123456789');
    expect(dto.rating).toBe(5);
    expect(dto.comment).toBe('Excellent product!');
    expect(dto.createdAt).toBe('2024-01-01T00:00:00Z');
    expect(dto.updatedAt).toBe('2024-01-02T00:00:00Z');
  });

  test('should map review with snake_case properties', () => {
    const review = {
      id: 2,
      product_id: 20,
      email: 'jane@example.com',
      name: 'Jane Smith',
      no_telp: '08987654321',
      rating: '4',
      comment: 'Good product',
      created_at: '2024-02-01T00:00:00Z',
      updated_at: '2024-02-02T00:00:00Z'
    };

    const dto = new ReviewInformation(review);

    expect(dto.id).toBe(2);
    expect(dto.productId).toBe(20);
    expect(dto.email).toBe('jane@example.com');
    expect(dto.name).toBe('Jane Smith');
    expect(dto.noTelp).toBe('08987654321');
    expect(dto.rating).toBe(4);
    expect(dto.comment).toBe('Good product');
    expect(dto.createdAt).toBe('2024-02-01T00:00:00Z');
    expect(dto.updatedAt).toBe('2024-02-02T00:00:00Z');
  });

  test('should convert string rating to integer', () => {
    const review = {
      id: 3,
      productId: 30,
      email: 'test@test.com',
      name: 'Test User',
      noTelp: '08111111111',
      rating: '5',
      comment: 'Test comment',
      createdAt: '2024-01-01',
      updatedAt: '2024-01-01'
    };

    const dto = new ReviewInformation(review);

    expect(typeof dto.rating).toBe('number');
    expect(dto.rating).toBe(5);
  });
});
