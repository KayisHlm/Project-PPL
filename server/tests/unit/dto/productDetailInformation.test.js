const ProductDetailInformation = require('../../../src/dto/product/productDetailInformation');

describe('ProductDetailInformation DTO', () => {
  test('should extend ProductInformation and add extended fields', () => {
    const product = {
      id: 1,
      seller_id: 5,
      name: 'Test Product',
      price: '99.99',
      weight: 500,
      stock: 10,
      category: 'Electronics',
      description: 'Test description',
      created_at: '2024-01-01T00:00:00Z',
      updated_at: '2024-01-02T00:00:00Z',
      shop_name: 'Test Shop',
      shop_province: 'DKI Jakarta',
      shop_city: 'Jakarta Selatan',
      shop_district: 'Kebayoran Baru',
      shop_village: 'Gunung',
      images: [
        {
          id: 1,
          product_id: 1,
          image_url: 'http://example.com/image1.jpg',
          created_at: '2024-01-01',
          updated_at: '2024-01-01'
        }
      ],
      review_count: '5',
      average_rating: '4.5'
    };

    const dto = new ProductDetailInformation(product);

    // Check inherited properties
    expect(dto.id).toBe(1);
    expect(dto.sellerId).toBe(5);
    expect(dto.name).toBe('Test Product');
    expect(dto.price).toBe(99.99);

    // Check extended properties
    expect(dto.shopName).toBe('Test Shop');
    expect(dto.shopProvince).toBe('DKI Jakarta');
    expect(dto.shopCity).toBe('Jakarta Selatan');
    expect(dto.shopDistrict).toBe('Kebayoran Baru');
    expect(dto.shopVillage).toBe('Gunung');
    expect(dto.images).toHaveLength(1);
    expect(dto.images[0].imageUrl).toBe('http://example.com/image1.jpg');
    expect(dto.reviewCount).toBe(5);
    expect(dto.averageRating).toBe(4.5);
  });

  test('should handle empty images array', () => {
    const product = {
      id: 2,
      seller_id: 10,
      name: 'Product No Images',
      price: '50.00',
      weight: 100,
      stock: 5,
      category: 'Test',
      description: 'Test',
      created_at: '2024-01-01',
      updated_at: '2024-01-01',
      shop_name: 'Shop',
      shop_province: 'Province',
      shop_city: 'City',
      shop_district: 'District',
      shop_village: 'Village',
      images: [],
      review_count: '0',
      average_rating: '0'
    };

    const dto = new ProductDetailInformation(product);

    expect(dto.images).toEqual([]);
    expect(dto.reviewCount).toBe(0);
    expect(dto.averageRating).toBe(0);
  });

  test('should include reviews array when available', () => {
    const product = {
      id: 3,
      seller_id: 15,
      name: 'Product With Reviews',
      price: '75.00',
      weight: 200,
      stock: 3,
      category: 'Test',
      description: 'Test',
      created_at: '2024-01-01',
      updated_at: '2024-01-01',
      shop_name: 'Shop',
      shop_province: 'Province',
      shop_city: 'City',
      shop_district: 'District',
      shop_village: 'Village',
      images: [],
      review_count: '2',
      average_rating: '4.0',
      reviews: [
        { id: 1, rating: 5, comment: 'Great!' },
        { id: 2, rating: 3, comment: 'Good' }
      ]
    };

    const dto = new ProductDetailInformation(product);

    expect(dto.reviews).toBeDefined();
    expect(dto.reviews).toHaveLength(2);
    expect(dto.reviews[0].rating).toBe(5);
  });
});
