const GetReviewById = require("../../../usecases/review/getReviewById");
const ReviewRepository = require("../../repositories/reviewRepository");

async function getById(req, res) {
  try {
    const reviewId = req.params.id;
    if (!reviewId) {
      return res.status(400).json({ 
        code: 400, 
        message: "Review ID is required" 
      });
    }

    const usecase = new GetReviewById(new ReviewRepository());
    const review = await usecase.execute(reviewId);
    
    if (!review) {
      return res.status(404).json({ 
        code: 404, 
        message: "Review not found" 
      });
    }

    return res.status(200).json({ 
      code: 200, 
      data: review 
    });
  } catch (error) {
    return res.status(500).json({ 
      code: 500, 
      message: "Internal Server Error", 
      error: error.message 
    });
  }
}

module.exports = { getById };
