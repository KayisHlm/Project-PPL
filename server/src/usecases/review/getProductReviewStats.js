class GetProductReviewStats {
  constructor(reviewRepository) {
    this.reviewRepository = reviewRepository;
  }

  async execute(productId) {
    const { avgRating, totalReviews } = await this.reviewRepository.getAverageRating(productId);
    const distribution = await this.reviewRepository.getRatingDistribution(productId);

    return {
      averageRating: avgRating,
      totalReviews,
      ratingDistribution: distribution
    };
  }
}

module.exports = GetProductReviewStats;
