const ProductInformation = require('./productInformation');
const ImageProductInformation = require('../imageProduct/imageProductInformation');

class ProductDetailInformation extends ProductInformation {
    constructor(product) {
        super(product);
        
        // Shop information
        this.shopName = product.shop_name;
        this.shopProvince = product.shop_province;
        this.shopCity = product.shop_city;
        this.shopDistrict = product.shop_district;
        this.shopVillage = product.shop_village;
        
        // Images
        this.images = (product.images || []).map(image => 
            image instanceof ImageProductInformation ? image : new ImageProductInformation(image)
        );
        
        // Review statistics
        this.reviewCount = parseInt(product.review_count || 0);
        this.averageRating = parseFloat(product.average_rating || 0);
        
        // Reviews array (if available)
        if (product.reviews) {
            this.reviews = product.reviews;
        }
    }
}

module.exports = ProductDetailInformation;
