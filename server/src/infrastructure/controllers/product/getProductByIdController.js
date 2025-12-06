const GetProductById = require("../../../usecases/product/getProductById");
const ProductRepository = require("../../repositories/productRepository");
const ReviewRepository = require("../../repositories/reviewRepository");
const ProductCompleteInformation = require("../../../dto/product/productCompleteInformation");

async function getById(req, res) {
  try {
    const { id } = req.params;
    
    if (!id) {
      return res.status(400).json({
        code: 400,
        message: "Product ID is required"
      });
    }

    const usecase = new GetProductById(new ProductRepository(), new ReviewRepository());
    const product = await usecase.execute(id);
    
    if (!product) {
      return res.status(404).json({
        code: 404,
        message: "Product not found"
      });
    }

    // Map to DTO
    const productDTO = new ProductCompleteInformation(product);

    return res.status(200).json({
      code: 200,
      data: productDTO
    });
  } catch (error) {
    console.error('Error getting product by ID:', error);
    return res.status(500).json({
      code: 500,
      message: "Internal Server Error",
      error: error.message
    });
  }
}

module.exports = { getById };
