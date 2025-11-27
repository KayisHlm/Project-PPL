const { BadRequest } = require("../../domain/errors");

class CreateReview {
  constructor(reviewRepository, productRepository) {
    this.reviewRepository = reviewRepository;
    this.productRepository = productRepository;
  }

  async execute(productId, body) {
    // Validate product exists
    const product = await this.productRepository.findById(productId);
    if (!product) {
      throw new BadRequest("Product not found");
    }

    // Validate required fields based on actual database schema
    const required = ["email", "name", "rating"];
    for (const f of required) {
      if (!body[f] && body[f] !== 0) {
        throw new BadRequest(`Field '${f}' is required`);
      }
    }

    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(body.email)) {
      throw new BadRequest("Invalid email format");
    }

    // Validate rating
    const rating = parseInt(body.rating, 10);
    if (isNaN(rating) || rating < 1 || rating > 5) {
      throw new BadRequest("rating must be an integer between 1 and 5");
    }

    // Validate name length
    if (body.name.trim().length < 2) {
      throw new BadRequest("name must be at least 2 characters");
    }
    if (body.name.trim().length > 255) {
      throw new BadRequest("name must not exceed 255 characters");
    }

    // Optional fields validation
    if (body.no_telp && typeof body.no_telp !== 'string') {
      throw new BadRequest("no_telp must be a string");
    }
    if (body.no_telp && body.no_telp.length > 20) {
      throw new BadRequest("no_telp must not exceed 20 characters");
    }
    if (body.comment && typeof body.comment !== 'string') {
      throw new BadRequest("comment must be a string");
    }

    // Check if user already reviewed this product
    const hasReviewed = await this.reviewRepository.checkUserReviewed(productId, body.email);
    if (hasReviewed) {
      throw new BadRequest("You have already reviewed this product");
    }

    // Prepare data for repository
    const reviewData = {
      email: body.email.trim(),
      name: body.name.trim(),
      no_telp: body.no_telp ? body.no_telp.trim() : null,
      rating,
      comment: body.comment ? body.comment.trim() : null
    };

    // Create review
    const created = await this.reviewRepository.create(productId, reviewData);

    // Update product rating
    const { avgRating } = await this.reviewRepository.getAverageRating(productId);
    await this.productRepository.updateRating(productId, avgRating);

    return created;
  }
}

module.exports = CreateReview;
