const ImageProductInformation = require('../../../src/dto/imageProduct/imageProductInformation');

describe('ImageProductInformation DTO', () => {
  test('should map image with camelCase properties', () => {
    const image = {
      id: 1,
      productId: 10,
      imageUrl: 'http://example.com/image.jpg',
      createdAt: '2024-01-01T00:00:00Z',
      updatedAt: '2024-01-02T00:00:00Z'
    };

    const dto = new ImageProductInformation(image);

    expect(dto.id).toBe(1);
    expect(dto.productId).toBe(10);
    expect(dto.imageUrl).toBe('http://example.com/image.jpg');
    expect(dto.createdAt).toBe('2024-01-01T00:00:00Z');
    expect(dto.updatedAt).toBe('2024-01-02T00:00:00Z');
  });

  test('should map image with snake_case properties', () => {
    const image = {
      id: 2,
      product_id: 20,
      image_url: 'http://example.com/image2.jpg',
      created_at: '2024-02-01T00:00:00Z',
      updated_at: '2024-02-02T00:00:00Z'
    };

    const dto = new ImageProductInformation(image);

    expect(dto.id).toBe(2);
    expect(dto.productId).toBe(20);
    expect(dto.imageUrl).toBe('http://example.com/image2.jpg');
    expect(dto.createdAt).toBe('2024-02-01T00:00:00Z');
    expect(dto.updatedAt).toBe('2024-02-02T00:00:00Z');
  });
});
