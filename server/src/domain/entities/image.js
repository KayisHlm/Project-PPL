class image {
  constructor({ 
    id,
    product_id, 
    image_url, 
    created_at, 
    updated_at 
}) {
    this.id = id;
    this.productId = product_id;
    this.imageUrl = image_url;
    this.createdAt = created_at;
    this.updatedAt = updated_at;
  }
}

module.exports = image;