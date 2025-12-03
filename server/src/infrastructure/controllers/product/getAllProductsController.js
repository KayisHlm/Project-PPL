const GetAllProducts = require("../../../usecases/product/getAllProducts");
const ProductRepository = require("../../repositories/productRepository");
const ProductInformation = require("../../../dto/product/productInformation");
const ImageProductInformation = require("../../../dto/imageProduct/imageProductInformation");

async function getAll(req, res) {
  try {
    const usecase = new GetAllProducts(new ProductRepository());
    const products = await usecase.execute();
    
    // Map to DTO using class constructors
    const productsDTO = products.map(product => {
      const productDTO = new ProductInformation(product);
      productDTO.images = product.images.map(image => new ImageProductInformation(image));
      productDTO.shop_name = product.shop_name;
      productDTO.review_count = product.review_count;
      productDTO.average_rating = parseFloat(product.average_rating);
      return productDTO;
    });
    
    return res.status(200).json({
      code: 200,
      data: productsDTO
    });
  } catch (error) {
    return res.status(500).json({
      code: 500,
      message: "Internal Server Error",
      error: error.message
    });
  }
}

module.exports = { getAll };
