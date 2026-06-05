const calculateAverageRating = require('../../domain/helpers/calculateAverageRating');

class GetProductReviewStats {
  constructor(reviewRepository) {
    this.reviewRepository = reviewRepository;
  }

  async execute(productId) {
    const { avgRating, totalReviews } = await this.reviewRepository.getAverageRating(productId);
    const distribution = await this.reviewRepository.getRatingDistribution(productId);

    // Hitung ulang rata-rata dari distribusi sebagai verifikasi
    const ratingsArray = [];
    if (distribution && distribution.length > 0) {
      distribution.forEach(d => {
        const count = parseInt(d.count, 10);
        const rating = parseInt(d.rating, 10);
        for (let i = 0; i < count; i++) {
          ratingsArray.push(rating);
        }
      });
    }
    const calculatedAvg = calculateAverageRating(ratingsArray);

    return {
      averageRating: calculatedAvg,
      totalReviews,
      ratingDistribution: distribution
    };
  }
}

module.exports = GetProductReviewStats;
