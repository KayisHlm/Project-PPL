const CreateProductWithImages = require("../../../usecases/product/createProductWithImage");
const ProductRepository = require("../../repositories/productRepository");
const ImageRepository = require("../../repositories/imageProductRepository");
const ProductInformation = require("../../../dto/product/productInformation");
const ImageProductInformation = require("../../../dto/imageProduct/imageProductInformation");

async function create(req, res) {
  try {
    const sellerId = req.user?.sellerId;
    if (!sellerId) {
      return res.status(400).json({ 
        code: 400, 
        message: "sellerId not found" 
      });
    }

    const { image_urls, imageUrls, ...productData } = req.body;
    const urls = image_urls || imageUrls;

    const usecase = new CreateProductWithImages(
      new ProductRepository(),
      new ImageRepository()
    );

    const result = await usecase.execute(sellerId, productData, urls);

    // Map to DTO using class constructors
    const productDTO = new ProductInformation(result.product);
    const imagesDTO = result.images.map(image => new ImageProductInformation(image));

    return res.status(201).json({
      code: 201,
      message: "Product created successfully",
      data: {
        product: productDTO,
        images: imagesDTO
      }
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
