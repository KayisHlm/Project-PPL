class GetReviewById {
  constructor(reviewRepository) {
    this.reviewRepository = reviewRepository;
  }

  async execute(reviewId) {
    const review = await this.reviewRepository.findById(reviewId);
    return review;
  }
}

module.exports = GetReviewById;
