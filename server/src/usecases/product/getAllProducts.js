class GetAllProducts {
  constructor(productRepository) {
    this.productRepository = productRepository;
  }

  async execute() {
    // Get all products with images, shop name, and review count in single query (optimized)
    return await this.productRepository.listAllProduct();
  }
}

module.exports = GetAllProducts;
