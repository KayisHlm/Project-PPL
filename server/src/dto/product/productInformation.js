class productInformation {
    constructor (product) {
        this.id = product.id;
        this.sellerId = product.sellerId || product.seller_id;
        this.name = product.name;
        this.price = parseFloat(product.price);
        this.weight = product.weight;
        this.stock = product.stock;
        this.category = product.category;
        this.description = product.description;
        this.rating = parseFloat(product.rating || 0);
        this.createdAt = product.createdAt || product.created_at;
        this.updatedAt = product.updatedAt || product.updated_at;
    }
}

module.exports = productInformation;