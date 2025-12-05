const CreateReview = require("../../../usecases/review/createReview");
const ReviewRepository = require("../../repositories/reviewRepository");
const ProductRepository = require("../../repositories/productRepository");
const ReviewInformation = require("../../../dto/review/reviewInformation");
const mailer = require("../../services/mailer");
const { BadRequest, NotFound, AlreadyExist, InternalServerError } = require("../../../domain/errors");

async function create(req, res) {
  try {
    const { productId } = req.params;
    
    const reviewRepository = new ReviewRepository();
    const productRepository = new ProductRepository();
    const usecase = new CreateReview(reviewRepository, productRepository, mailer);
    
    const review = await usecase.execute(productId, req.body);
    console.log("[CreateReviewController] Incoming body:", req.body);

    // Map to DTO
    const reviewDTO = new ReviewInformation(review);

    return res.status(201).json({ 
      code: 201, 
      message: "Review created successfully", 
      data: reviewDTO 
    });
  } catch (error) {
    console.error("[CreateReviewController] Error:", error);
    if (error.statusCode && error.message) {
      return res.status(error.statusCode).json({
        code: error.statusCode,
        message: error.message
      });
    }

    return res.status(500).json({
      code: 500,
      message: "Internal Server Error"
    });
  }
}

module.exports = { create };
