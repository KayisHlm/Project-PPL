/**
 * Complete DTO untuk produk dengan semua informasi detail
 * Menggabungkan ProductDetailInformation dan ProductWithDetailsInformation
 * 
 * Use Cases:
 * - Public API (GET /api/products, GET /api/products/:id)
 * - Seller Dashboard (GET /api/products/user/products)
 * - Admin Panel
 * 
 * Features:
 * - Complete product information
 * - Full shop address details
 * - Images array with proper mapping
 * - Reviews array with province data
 * - Review statistics
 * - Validation methods
 * - Analytics helpers
 */
class ProductCompleteInformation {
    constructor(product) {
        // Basic product information
        this.id = product.id;
        this.sellerId = product.sellerId || product.seller_id;
        this.name = product.name;
        this.price = parseFloat(product.price);
        this.weight = product.weight;
        this.stock = product.stock;
        this.category = product.category;
        this.description = product.description;
        this.createdAt = product.createdAt || product.created_at;
        this.updatedAt = product.updatedAt || product.updated_at;

        // Complete shop information
        this.shopName = product.shopName || product.shop_name;
        this.shopProvince = product.shopProvince || product.shop_province;
        this.shopCity = product.shopCity || product.shop_city;
        this.shopDistrict = product.shopDistrict || product.shop_district;
        this.shopVillage = product.shopVillage || product.shop_village;

        // Images array with proper field mapping
        this.images = Array.isArray(product.images) ? product.images.map(img => ({
            id: img.id,
            productId: img.productId || img.product_id,
            imageUrl: img.imageUrl || img.image_url,
            isCover: img.isCover || img.is_cover || false,
            createdAt: img.createdAt || img.created_at,
            updatedAt: img.updatedAt || img.updated_at
        })) : [];

        // Reviews array with complete data including province
        this.reviews = Array.isArray(product.reviews) ? product.reviews.map(review => ({
            id: review.id,
            rating: review.rating,
            comment: review.comment,
            name: review.name,
            email: review.email,
            noTelp: review.noTelp || review.no_telp,
            province: review.province,
            createdAt: review.createdAt || review.created_at
        })) : [];

        // Review statistics
        this.reviewCount = parseInt(product.reviewCount || product.review_count || 0);
        this.averageRating = parseFloat(product.averageRating || product.average_rating || 0);
    }

    // ==================== Validation Methods ====================

    /**
     * Validate if all required fields are present
     * @returns {boolean} true if product data is valid
     */
    isValid() {
        return !!(this.id && this.sellerId && this.name && this.price >= 0);
    }

    /**
     * Check if product has complete shop information
     * @returns {boolean} true if all shop fields are present
     */
    hasCompleteShopInfo() {
        return !!(
            this.shopName &&
            this.shopProvince &&
            this.shopCity &&
            this.shopDistrict &&
            this.shopVillage
        );
    }

    /**
     * Check if product has images
     * @returns {boolean} true if product has at least one image
     */
    hasImages() {
        return this.images.length > 0;
    }

    /**
     * Check if product has reviews
     * @returns {boolean} true if product has at least one review
     */
    hasReviews() {
        return this.reviewCount > 0 && this.reviews.length > 0;
    }

    // ==================== Business Logic Methods ====================

    /**
     * Get total stock value (price * stock)
     * @returns {number} total value in currency
     */
    getTotalValue() {
        return this.price * this.stock;
    }

    /**
     * Check if product is in stock
     * @returns {boolean} true if stock > 0
     */
    isInStock() {
        return this.stock > 0;
    }

    /**
     * Get stock status label
     * @returns {string} "In Stock", "Low Stock", or "Out of Stock"
     */
    getStockStatus() {
        if (this.stock === 0) return 'Out of Stock';
        if (this.stock <= 10) return 'Low Stock';
        return 'In Stock';
    }

    /**
     * Get cover image or first image
     * @returns {object|null} cover image object or null if no images
     */
    getCoverImage() {
        const coverImage = this.images.find(img => img.isCover);
        return coverImage || this.images[0] || null;
    }

    /**
     * Get complete shop address as string
     * @returns {string} formatted address
     */
    getFullAddress() {
        const parts = [
            this.shopVillage,
            this.shopDistrict,
            this.shopCity,
            this.shopProvince
        ].filter(Boolean);
        
        return parts.length > 0 ? parts.join(', ') : '';
    }

    // ==================== Review Analytics Methods ====================

    /**
     * Get unique provinces from reviews
     * @returns {Array<string>} array of unique province names
     */
    getReviewProvinces() {
        const provinces = this.reviews
            .map(r => r.province)
            .filter(p => p && p.trim() !== '');
        return [...new Set(provinces)];
    }

    /**
     * Group reviews by province with counts
     * @returns {Object} object with province as key and count as value
     * @example { 'DKI Jakarta': 5, 'Jawa Barat': 3 }
     */
    getReviewsByProvince() {
        const provinceMap = {};
        this.reviews.forEach(review => {
            const province = review.province;
            if (province && province.trim() !== '') {
                provinceMap[province] = (provinceMap[province] || 0) + 1;
            }
        });
        return provinceMap;
    }

    /**
     * Get reviews grouped by rating
     * @returns {Object} object with rating as key and array of reviews as value
     */
    getReviewsByRating() {
        const ratingMap = { 1: [], 2: [], 3: [], 4: [], 5: [] };
        this.reviews.forEach(review => {
            if (review.rating >= 1 && review.rating <= 5) {
                ratingMap[review.rating].push(review);
            }
        });
        return ratingMap;
    }

    /**
     * Get rating distribution as counts
     * @returns {Object} object with rating as key and count as value
     */
    getRatingDistribution() {
        const distribution = { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 };
        this.reviews.forEach(review => {
            if (review.rating >= 1 && review.rating <= 5) {
                distribution[review.rating]++;
            }
        });
        return distribution;
    }

    /**
     * Get latest N reviews
     * @param {number} limit number of reviews to return (default: 5)
     * @returns {Array} array of latest reviews
     */
    getLatestReviews(limit = 5) {
        return this.reviews.slice(0, limit);
    }

    /**
     * Calculate rating percentage (0-100)
     * @returns {number} rating percentage
     */
    getRatingPercentage() {
        return (this.averageRating / 5) * 100;
    }

    // ==================== Export Methods ====================

    /**
     * Convert to basic product info (without reviews and complex data)
     * @returns {Object} basic product information
     */
    toBasicInfo() {
        return {
            id: this.id,
            sellerId: this.sellerId,
            name: this.name,
            price: this.price,
            stock: this.stock,
            category: this.category,
            shopName: this.shopName,
            coverImage: this.getCoverImage(),
            reviewCount: this.reviewCount,
            averageRating: this.averageRating
        };
    }

    /**
     * Convert to JSON-friendly format
     * @returns {Object} plain object representation
     */
    toJSON() {
        return {
            id: this.id,
            sellerId: this.sellerId,
            name: this.name,
            price: this.price,
            weight: this.weight,
            stock: this.stock,
            category: this.category,
            description: this.description,
            createdAt: this.createdAt,
            updatedAt: this.updatedAt,
            shopName: this.shopName,
            shopProvince: this.shopProvince,
            shopCity: this.shopCity,
            shopDistrict: this.shopDistrict,
            shopVillage: this.shopVillage,
            images: this.images,
            reviews: this.reviews,
            reviewCount: this.reviewCount,
            averageRating: this.averageRating
        };
    }
}

module.exports = ProductCompleteInformation;
