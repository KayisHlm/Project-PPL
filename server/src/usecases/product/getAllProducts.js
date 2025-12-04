const { InternalServerError } = require("../../domain/errors");

class GetAllProducts {
  constructor(productRepository) {
    this.productRepository = productRepository;
  }

  async execute() {
    try {
      const products = await this.productRepository.listAllProduct();
      return products;
    } catch (error) {
      throw new InternalServerError("Failed to retrieve products");
    }
  }
}

module.exports = GetAllProducts;
