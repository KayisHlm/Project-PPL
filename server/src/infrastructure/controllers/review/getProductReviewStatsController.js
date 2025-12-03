const GetProductReviewStats = require("../../../usecases/review/getProductReviewStats");
const ReviewRepository = require("../../repositories/reviewRepository");

async function getProductStats(req, res) {
  try {
    const productId = req.params.productId;
    if (!productId) {
      return res.status(400).json({ 
        code: 400, 
        message: "Product ID is required" 
      });
    }

    const usecase = new GetProductReviewStats(new ReviewRepository());
    const stats = await usecase.execute(productId);
    
    return res.status(200).json({ 
      code: 200, 
      data: stats 
    });
  } catch (error) {
    return res.status(500).json({ 
      code: 500, 
      message: "Internal Server Error", 
      error: error.message 
    });
  }
}

module.exports = { getProductStats };
