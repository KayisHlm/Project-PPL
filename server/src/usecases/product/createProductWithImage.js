const { BadRequest } = require("../../domain/errors");

class CreateProductWithImages {
  constructor(productRepository, imageRepository) {
    this.productRepository = productRepository;
    this.imageRepository = imageRepository;
  }

  async execute(sellerId, productData, imageUrls) {
    // Validate required fields
    const required = ["name", "price", "weight", "stock"];
    for (const f of required) {
      if (!productData[f] && productData[f] !== 0) {
        throw new BadRequest(`Field '${f}' is required`);
      }
    }

    // Validate images (minimal 1, maksimal 6)
    if (!imageUrls || !Array.isArray(imageUrls) || imageUrls.length === 0) {
      throw new BadRequest("At least one product image is required");
    }

    if (imageUrls.length > 6) {
      throw new BadRequest("Maximum 6 images allowed");
    }

    // Validate each image URL
    for (const url of imageUrls) {
      if (typeof url !== 'string' || url.trim() === '') {
        throw new BadRequest("All image URLs must be valid strings");
      }
    }

    // Validate data types and ranges
    if (parseFloat(productData.price) < 0) {
      throw new BadRequest("Price must be positive");
    }

    if (parseInt(productData.stock) < 0) {
      throw new BadRequest("Stock cannot be negative");
    }

    if (parseInt(productData.weight) < 1) {
      throw new BadRequest("Weight must be at least 1 gram");
    }

    // Create product
    const product = await this.productRepository.create(sellerId, {
      name: productData.name,
      price: parseFloat(productData.price),
      weight: parseInt(productData.weight, 10),
      stock: parseInt(productData.stock, 10),
      category: productData.category || null,
      description: productData.description || null
    });

    // Create images (first one will be marked as cover via is_cover field)
    const images = await this.imageRepository.createBulk(product.id, imageUrls);

    return {
      product,
      images
    };
  }
}

module.exports = CreateProductWithImages;