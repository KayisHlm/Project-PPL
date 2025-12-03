class GetProductById {
  constructor(productRepository, reviewRepository) {
    this.productRepository = productRepository;
    this.reviewRepository = reviewRepository;
  }

  async execute(productId) {
    // Get product with images, shop name, review count and average rating
    const product = await this.productRepository.findByIdWithDetails(productId);
    
    if (!product) {
      return null;
    }

    // Get reviews for this product
    const reviews = await this.reviewRepository.listByProduct(productId);
    
    // Add reviews to product
    product.reviews = reviews;

    return product;
  }
}

module.exports = GetProductById;
