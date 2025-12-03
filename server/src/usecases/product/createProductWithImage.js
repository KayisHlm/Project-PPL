const { BadRequest } = require("../../domain/errors");

class CreateProductWithImages {
  constructor(productRepository, imageRepository) {
    this.productRepository = productRepository;
    this.imageRepository = imageRepository;
  }

  async execute(sellerId, productData, imageUrls) {
    // Validate product data
    const required = ["name", "price", "weight", "stock"];
    for (const f of required) {
      if (!productData[f] && productData[f] !== 0) {
        throw new BadRequest(`Field '${f}' is required`);
      }
    }

    // Validate images (WAJIB ada minimal 1 gambar)
    if (!imageUrls || !Array.isArray(imageUrls) || imageUrls.length === 0) {
      throw new BadRequest("At least one product image is required");
    }

    // Validate each image URL
    for (const url of imageUrls) {
      if (typeof url !== 'string' || url.trim().length === 0) {
        throw new BadRequest("Each image_url must be a non-empty string");
      }
      try {
        new URL(url);
      } catch (error) {
        throw new BadRequest(`Invalid URL format: ${url}`);
      }
    }

    // Create product (atomic transaction jika pakai database transaction)
    const product = await this.productRepository.create(sellerId, {
      name: productData.name,
      price: parseFloat(productData.price),
      weight: parseInt(productData.weight, 10),
      stock: parseInt(productData.stock, 10),
      category: productData.category || null,
      description: productData.description || null
    });

    // Create images
    const images = await this.imageRepository.createBulk(product.id, imageUrls);

    return {
      product,
      images
    };
  }
}

module.exports = CreateProductWithImages;