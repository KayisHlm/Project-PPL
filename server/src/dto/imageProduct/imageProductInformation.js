class ImageProductInformation {
    constructor(image) {
        this.id = image.id;
        this.productId = image.productId || image.product_id;
        this.imageUrl = image.imageUrl || image.image_url;
        this.createdAt = image.createdAt || image.created_at;
        this.updatedAt = image.updatedAt || image.updated_at;
    }
}

module.exports = ImageProductInformation;