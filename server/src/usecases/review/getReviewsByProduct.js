class GetReviewsByProduct {
  constructor(reviewRepository) {
    this.reviewRepository = reviewRepository;
  }

  async execute(productId) {
    const reviews = await this.reviewRepository.listByProduct(productId);
    return reviews;
  }
}

module.exports = GetReviewsByProduct;
