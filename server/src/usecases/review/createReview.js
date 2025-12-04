const { BadRequest, NotFound, AlreadyExist, InternalServerError } = require("../../domain/errors");

class CreateReview {
  constructor(reviewRepository, productRepository, mailer) {
    this.reviewRepository = reviewRepository;
    this.productRepository = productRepository;
    this.mailer = mailer;
  }

  async execute(productId, body) {
    // Validate productId
    if (!productId) {
      throw new BadRequest("Product ID is required");
    }

    // Validate product exists
    const product = await this.productRepository.findById(productId);
    if (!product) {
      throw new NotFound("Product not found");
    }

    // Validate required fields
    const required = ["email", "name", "rating"];
    for (const field of required) {
      if (!body[field] && body[field] !== 0) {
        throw new BadRequest(`Field '${field}' is required`);
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
      throw new BadRequest("Rating must be an integer between 1 and 5");
    }

    // Validate name length
    if (body.name.trim().length < 2) {
      throw new BadRequest("Name must be at least 2 characters");
    }
    if (body.name.trim().length > 255) {
      throw new BadRequest("Name must not exceed 255 characters");
    }

    // Optional fields validation
    if (body.no_telp && typeof body.no_telp !== 'string') {
      throw new BadRequest("Phone number must be a string");
    }
    if (body.no_telp && body.no_telp.length > 20) {
      throw new BadRequest("Phone number must not exceed 20 characters");
    }
    if (body.comment && typeof body.comment !== 'string') {
      throw new BadRequest("Comment must be a string");
    }
    if (body.province && typeof body.province !== 'string') {
      throw new BadRequest("Province must be a string");
    }
    if (body.province && body.province.length > 255) {
      throw new BadRequest("Province must not exceed 255 characters");
    }

    // Check if user already reviewed this product
    const hasReviewed = await this.reviewRepository.checkUserReviewed(productId, body.email);
    if (hasReviewed) {
      throw new AlreadyExist("You have already reviewed this product");
    }

    // Prepare data for repository
    const reviewData = {
      email: body.email.trim(),
      name: body.name.trim(),
      no_telp: body.no_telp ? body.no_telp.trim() : null,
      rating,
      comment: body.comment ? body.comment.trim() : null,
      province: body.province ? body.province.trim() : null
    };

    try {
      // Create review
      const created = await this.reviewRepository.create(productId, reviewData);

      // mailer makasih
      try {
          if (this.mailer && this.mailer.sendThankyouEmail) {
              await this.mailer.sendThankyouEmail(created, product);
          }
      } catch (emailErr) {
          console.error('ThankYouEmail: failed to send Thank You email', emailErr);
      }
      return created;
    } catch (error) {
      throw new InternalServerError("Failed to create review");
    }
  }
}

module.exports = CreateReview;
