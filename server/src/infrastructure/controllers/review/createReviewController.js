const CreateReview = require("../../../usecases/review/createReview");
const ReviewRepository = require("../../repositories/reviewRepository");
const ProductRepository = require("../../repositories/productRepository");

async function create(req, res) {
  try {
    console.log('[CREATE REVIEW] Request received:', {
      productId: req.params.productId,
      body: req.body,
      headers: req.headers
    });
    
    const productId = req.params.productId;
    if (!productId) {
      return res.status(400).json({ 
        code: 400, 
        message: "Product ID is required" 
      });
    }

    const usecase = new CreateReview(new ReviewRepository(), new ProductRepository());
    const review = await usecase.execute(productId, req.body);
    
    console.log('[CREATE REVIEW] Review created successfully:', review);
    
    return res.status(201).json({ 
      code: 201, 
      message: "Review created successfully", 
      data: review 
    });
  } catch (error) {
    if (error.statusCode) {
      return res.status(error.statusCode).json({ 
        code: error.statusCode, 
        message: error.message 
      });
    }
    return res.status(500).json({ 
      code: 500, 
      message: "Internal Server Error", 
      error: error.message 
    });
  }
}

module.exports = { create };
