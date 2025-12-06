const ProductCompleteInformation = require('../../../src/dto/product/productCompleteInformation');

describe('ProductCompleteInformation DTO', () => {
    const mockProduct = {
        id: 1,
        seller_id: 10,
        name: 'Test Product',
        price: 150000,
        weight: 500,
        stock: 100,
        category: 'Electronics',
        description: 'Test description',
        created_at: '2024-01-01T00:00:00Z',
        updated_at: '2024-01-02T00:00:00Z',
        shop_name: 'Test Shop',
        shop_province: 'DKI Jakarta',
        shop_city: 'Jakarta Selatan',
        shop_district: 'Kebayoran Baru',
        shop_village: 'Senayan',
        images: [
            {
                id: 1,
                product_id: 1,
                image_url: 'http://example.com/image1.jpg',
                is_cover: true,
                created_at: '2024-01-01T00:00:00Z',
                updated_at: '2024-01-01T00:00:00Z'
            },
            {
                id: 2,
                product_id: 1,
                image_url: 'http://example.com/image2.jpg',
                is_cover: false,
                created_at: '2024-01-01T00:00:00Z',
                updated_at: '2024-01-01T00:00:00Z'
            }
        ],
        reviews: [
            {
                id: 1,
                rating: 5,
                comment: 'Great product',
                name: 'John Doe',
                email: 'john@example.com',
                no_telp: '081234567890',
                province: 'DKI Jakarta',
                created_at: '2024-01-01T00:00:00Z'
            },
            {
                id: 2,
                rating: 4,
                comment: 'Good',
                name: 'Jane Doe',
                email: 'jane@example.com',
                no_telp: '081234567891',
                province: 'Jawa Barat',
                created_at: '2024-01-02T00:00:00Z'
            },
            {
                id: 3,
                rating: 5,
                comment: 'Excellent',
                name: 'Bob Smith',
                email: 'bob@example.com',
                province: 'DKI Jakarta',
                created_at: '2024-01-03T00:00:00Z'
            }
        ],
        review_count: 3,
        average_rating: 4.67
    };

    describe('Constructor - Basic Fields', () => {
        it('should map all basic product fields correctly', () => {
            const dto = new ProductCompleteInformation(mockProduct);

            expect(dto.id).toBe(1);
            expect(dto.sellerId).toBe(10);
            expect(dto.name).toBe('Test Product');
            expect(dto.price).toBe(150000);
            expect(dto.weight).toBe(500);
            expect(dto.stock).toBe(100);
            expect(dto.category).toBe('Electronics');
            expect(dto.description).toBe('Test description');
        });

        it('should handle camelCase field names', () => {
            const camelCaseProduct = {
                id: 1,
                sellerId: 10,
                name: 'Test',
                price: 1000,
                stock: 10,
                shopName: 'Camel Shop',
                shopProvince: 'Bali',
                reviewCount: 5,
                averageRating: 4.5,
                images: [],
                reviews: []
            };
            const dto = new ProductCompleteInformation(camelCaseProduct);
            
            expect(dto.sellerId).toBe(10);
            expect(dto.shopName).toBe('Camel Shop');
            expect(dto.shopProvince).toBe('Bali');
            expect(dto.reviewCount).toBe(5);
            expect(dto.averageRating).toBe(4.5);
        });
    });

    describe('Constructor - Shop Information', () => {
        it('should map all shop fields correctly', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            
            expect(dto.shopName).toBe('Test Shop');
            expect(dto.shopProvince).toBe('DKI Jakarta');
            expect(dto.shopCity).toBe('Jakarta Selatan');
            expect(dto.shopDistrict).toBe('Kebayoran Baru');
            expect(dto.shopVillage).toBe('Senayan');
        });

        it('should handle missing shop fields', () => {
            const productWithoutShop = { ...mockProduct };
            delete productWithoutShop.shop_province;
            delete productWithoutShop.shop_city;
            
            const dto = new ProductCompleteInformation(productWithoutShop);
            
            expect(dto.shopName).toBe('Test Shop');
            expect(dto.shopProvince).toBeUndefined();
            expect(dto.shopCity).toBeUndefined();
        });
    });

    describe('Constructor - Images', () => {
        it('should transform images array correctly', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            
            expect(dto.images).toHaveLength(2);
            expect(dto.images[0].id).toBe(1);
            expect(dto.images[0].productId).toBe(1);
            expect(dto.images[0].imageUrl).toBe('http://example.com/image1.jpg');
            expect(dto.images[0].isCover).toBe(true);
            expect(dto.images[1].isCover).toBe(false);
        });

        it('should handle empty images array', () => {
            const productWithoutImages = { ...mockProduct, images: [] };
            const dto = new ProductCompleteInformation(productWithoutImages);
            
            expect(dto.images).toEqual([]);
        });

        it('should handle missing images', () => {
            const productWithoutImages = { ...mockProduct };
            delete productWithoutImages.images;
            
            const dto = new ProductCompleteInformation(productWithoutImages);
            
            expect(dto.images).toEqual([]);
        });
    });

    describe('Constructor - Reviews', () => {
        it('should transform reviews array correctly', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            
            expect(dto.reviews).toHaveLength(3);
            expect(dto.reviews[0].id).toBe(1);
            expect(dto.reviews[0].rating).toBe(5);
            expect(dto.reviews[0].province).toBe('DKI Jakarta');
            expect(dto.reviews[0].name).toBe('John Doe');
            expect(dto.reviews[0].noTelp).toBe('081234567890');
        });

        it('should handle empty reviews array', () => {
            const productWithoutReviews = { ...mockProduct, reviews: [] };
            const dto = new ProductCompleteInformation(productWithoutReviews);
            
            expect(dto.reviews).toEqual([]);
        });

        it('should parse review statistics correctly', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            
            expect(dto.reviewCount).toBe(3);
            expect(dto.averageRating).toBe(4.67);
        });
    });

    describe('Validation Methods', () => {
        it('isValid() should return true for valid product', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            expect(dto.isValid()).toBe(true);
        });

        it('isValid() should return false if required fields missing', () => {
            const invalidProduct = { ...mockProduct, id: null };
            const dto = new ProductCompleteInformation(invalidProduct);
            expect(dto.isValid()).toBe(false);
        });

        it('hasCompleteShopInfo() should return true if all shop fields present', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            expect(dto.hasCompleteShopInfo()).toBe(true);
        });

        it('hasCompleteShopInfo() should return false if shop fields missing', () => {
            const incompleteProduct = { ...mockProduct };
            delete incompleteProduct.shop_province;
            const dto = new ProductCompleteInformation(incompleteProduct);
            expect(dto.hasCompleteShopInfo()).toBe(false);
        });

        it('hasImages() should return true if product has images', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            expect(dto.hasImages()).toBe(true);
        });

        it('hasImages() should return false if no images', () => {
            const noImageProduct = { ...mockProduct, images: [] };
            const dto = new ProductCompleteInformation(noImageProduct);
            expect(dto.hasImages()).toBe(false);
        });

        it('hasReviews() should return true if product has reviews', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            expect(dto.hasReviews()).toBe(true);
        });

        it('hasReviews() should return false if no reviews', () => {
            const noReviewProduct = { ...mockProduct, review_count: 0, reviews: [] };
            const dto = new ProductCompleteInformation(noReviewProduct);
            expect(dto.hasReviews()).toBe(false);
        });
    });

    describe('Business Logic Methods', () => {
        it('getTotalValue() should calculate correctly', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            expect(dto.getTotalValue()).toBe(150000 * 100);
        });

        it('isInStock() should return true if stock > 0', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            expect(dto.isInStock()).toBe(true);
        });

        it('isInStock() should return false if stock = 0', () => {
            const outOfStock = { ...mockProduct, stock: 0 };
            const dto = new ProductCompleteInformation(outOfStock);
            expect(dto.isInStock()).toBe(false);
        });

        it('getStockStatus() should return correct status', () => {
            const inStock = new ProductCompleteInformation({ ...mockProduct, stock: 100 });
            expect(inStock.getStockStatus()).toBe('In Stock');

            const lowStock = new ProductCompleteInformation({ ...mockProduct, stock: 5 });
            expect(lowStock.getStockStatus()).toBe('Low Stock');

            const outOfStock = new ProductCompleteInformation({ ...mockProduct, stock: 0 });
            expect(outOfStock.getStockStatus()).toBe('Out of Stock');
        });

        it('getCoverImage() should return cover image', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            const coverImage = dto.getCoverImage();
            
            expect(coverImage).toBeDefined();
            expect(coverImage.isCover).toBe(true);
            expect(coverImage.imageUrl).toBe('http://example.com/image1.jpg');
        });

        it('getCoverImage() should return first image if no cover', () => {
            const noCoverProduct = {
                ...mockProduct,
                images: [{ id: 1, product_id: 1, image_url: 'test.jpg', is_cover: false }]
            };
            const dto = new ProductCompleteInformation(noCoverProduct);
            const coverImage = dto.getCoverImage();
            
            expect(coverImage).toBeDefined();
            expect(coverImage.imageUrl).toBe('test.jpg');
        });

        it('getCoverImage() should return null if no images', () => {
            const noImageProduct = { ...mockProduct, images: [] };
            const dto = new ProductCompleteInformation(noImageProduct);
            expect(dto.getCoverImage()).toBeNull();
        });

        it('getFullAddress() should return formatted address', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            expect(dto.getFullAddress()).toBe('Senayan, Kebayoran Baru, Jakarta Selatan, DKI Jakarta');
        });

        it('getFullAddress() should handle missing fields', () => {
            const partialAddress = {
                ...mockProduct,
                shop_village: null,
                shop_district: null
            };
            const dto = new ProductCompleteInformation(partialAddress);
            expect(dto.getFullAddress()).toBe('Jakarta Selatan, DKI Jakarta');
        });
    });

    describe('Review Analytics Methods', () => {
        it('getReviewProvinces() should return unique provinces', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            const provinces = dto.getReviewProvinces();
            
            expect(provinces).toHaveLength(2);
            expect(provinces).toContain('DKI Jakarta');
            expect(provinces).toContain('Jawa Barat');
        });

        it('getReviewsByProvince() should group correctly', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            const provinceMap = dto.getReviewsByProvince();
            
            expect(provinceMap['DKI Jakarta']).toBe(2);
            expect(provinceMap['Jawa Barat']).toBe(1);
        });

        it('getReviewsByRating() should group by rating', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            const ratingMap = dto.getReviewsByRating();
            
            expect(ratingMap[5]).toHaveLength(2);
            expect(ratingMap[4]).toHaveLength(1);
            expect(ratingMap[3]).toHaveLength(0);
        });

        it('getRatingDistribution() should count ratings', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            const distribution = dto.getRatingDistribution();
            
            expect(distribution[5]).toBe(2);
            expect(distribution[4]).toBe(1);
            expect(distribution[3]).toBe(0);
        });

        it('getLatestReviews() should return limited reviews', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            const latest = dto.getLatestReviews(2);
            
            expect(latest).toHaveLength(2);
            expect(latest[0].id).toBe(1);
        });

        it('getRatingPercentage() should calculate percentage', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            expect(dto.getRatingPercentage()).toBeCloseTo(93.4, 1);
        });
    });

    describe('Export Methods', () => {
        it('toBasicInfo() should return simplified data', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            const basicInfo = dto.toBasicInfo();
            
            expect(basicInfo.id).toBe(1);
            expect(basicInfo.name).toBe('Test Product');
            expect(basicInfo.coverImage).toBeDefined();
            expect(basicInfo.reviewCount).toBe(3);
            expect(basicInfo).not.toHaveProperty('reviews');
            expect(basicInfo).not.toHaveProperty('description');
        });

        it('toJSON() should return complete data', () => {
            const dto = new ProductCompleteInformation(mockProduct);
            const json = dto.toJSON();
            
            expect(json.id).toBe(1);
            expect(json.shopProvince).toBe('DKI Jakarta');
            expect(json.images).toHaveLength(2);
            expect(json.reviews).toHaveLength(3);
        });
    });
});
