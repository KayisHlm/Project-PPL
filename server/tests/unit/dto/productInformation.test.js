const ProductInformation = require('../../../src/dto/product/productInformation');

describe('ProductInformation DTO', () => {
  test('should map product with camelCase properties', () => {
    const product = {
      id: 1,
      sellerId: 5,
      name: 'Test Product',
      price: '99.99',
      weight: 500,
      stock: 10,
      category: 'Electronics',
      description: 'Test description',
      createdAt: '2024-01-01T00:00:00Z',
      updatedAt: '2024-01-02T00:00:00Z'
    };

    const dto = new ProductInformation(product);

    expect(dto.id).toBe(1);
    expect(dto.sellerId).toBe(5);
    expect(dto.name).toBe('Test Product');
    expect(dto.price).toBe(99.99);
    expect(dto.weight).toBe(500);
    expect(dto.stock).toBe(10);
    expect(dto.category).toBe('Electronics');
    expect(dto.description).toBe('Test description');
    expect(dto.createdAt).toBe('2024-01-01T00:00:00Z');
    expect(dto.updatedAt).toBe('2024-01-02T00:00:00Z');
  });

  test('should map product with snake_case properties', () => {
    const product = {
      id: 2,
      seller_id: 10,
      name: 'Another Product',
      price: '149.50',
      weight: 1000,
      stock: 5,
      category: 'Clothing',
      description: 'Another description',
      created_at: '2024-02-01T00:00:00Z',
      updated_at: '2024-02-02T00:00:00Z'
    };

    const dto = new ProductInformation(product);

    expect(dto.id).toBe(2);
    expect(dto.sellerId).toBe(10);
    expect(dto.name).toBe('Another Product');
    expect(dto.price).toBe(149.50);
    expect(dto.weight).toBe(1000);
    expect(dto.stock).toBe(5);
    expect(dto.category).toBe('Clothing');
    expect(dto.description).toBe('Another description');
    expect(dto.createdAt).toBe('2024-02-01T00:00:00Z');
    expect(dto.updatedAt).toBe('2024-02-02T00:00:00Z');
  });

  test('should convert string price to float', () => {
    const product = {
      id: 3,
      sellerId: 15,
      name: 'Price Test',
      price: '299.99',
      weight: 200,
      stock: 20,
      category: 'Test',
      description: 'Test',
      createdAt: '2024-01-01',
      updatedAt: '2024-01-01'
    };

    const dto = new ProductInformation(product);

    expect(typeof dto.price).toBe('number');
    expect(dto.price).toBe(299.99);
  });
});
