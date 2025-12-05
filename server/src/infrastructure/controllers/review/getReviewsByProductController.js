const GetReviewsByProduct = require("../../../usecases/review/getReviewsByProduct");
const ReviewRepository = require("../../repositories/reviewRepository");
const ReviewInformation = require("../../../dto/review/reviewInformation");

async function listByProduct(req, res) {
  try {
    const productId = req.params.productId;
    if (!productId) {
      return res.status(400).json({ 
        code: 400, 
        message: "Product ID is required" 
      });
    }

    const usecase = new GetReviewsByProduct(new ReviewRepository());
    const reviews = await usecase.execute(productId);
    
    // Map to DTO
    const reviewsDTO = reviews.map(review => new ReviewInformation(review));
    
    return res.status(200).json({ 
      code: 200, 
      data: reviewsDTO 
    });
  } catch (error) {
    return res.status(500).json({ 
      code: 500, 
      message: "Internal Server Error", 
      error: error.message 
    });
  }
}

module.exports = { listByProduct };
