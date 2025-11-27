const { BadRequest, NotFound } = require("../../domain/errors");

class CreateImageProduct {
  constructor(imageRepository, productRepository) {
    this.imageRepository = imageRepository;
    this.productRepository = productRepository;
  }

  async execute(productId, body) {
    // Validate product exists
    const product = await this.productRepository.findById(productId);
    if (!product) {
      throw new NotFound(`Product with id ${productId} not found`);
    }

    // Validate required fields
    if (!body.image_url && !body.imageUrl) {
      throw new BadRequest("Field 'image_url' is required");
    }

    const imageUrl = body.image_url || body.imageUrl;

    // Validate image_url format
    if (typeof imageUrl !== 'string' || imageUrl.trim().length === 0) {
      throw new BadRequest("image_url must be a non-empty string");
    }

    // Optional: Validate URL format
    try {
      new URL(imageUrl);
    } catch (error) {
      throw new BadRequest("image_url must be a valid URL");
    }

    // Create image
    const created = await this.imageRepository.create(productId, imageUrl.trim());
    return created;
  }

  async executeBulk(productId, body) {
    // Validate product exists
    const product = await this.productRepository.findById(productId);
    if (!product) {
      throw new NotFound(`Product with id ${productId} not found`);
    }

    // Validate required fields
    if (!body.image_urls && !body.imageUrls) {
      throw new BadRequest("Field 'image_urls' is required");
    }

    const imageUrls = body.image_urls || body.imageUrls;

    // Validate image_urls is an array
    if (!Array.isArray(imageUrls)) {
      throw new BadRequest("image_urls must be an array");
    }

    if (imageUrls.length === 0) {
      throw new BadRequest("image_urls array cannot be empty");
    }

    // Validate each URL
    const validUrls = [];
    for (const url of imageUrls) {
      if (typeof url !== 'string' || url.trim().length === 0) {
        throw new BadRequest("Each image_url must be a non-empty string");
      }

      // Optional: Validate URL format
      try {
        new URL(url);
        validUrls.push(url.trim());
      } catch (error) {
        throw new BadRequest(`Invalid URL format: ${url}`);
      }
    }

    // Create images in bulk
    const created = await this.imageRepository.createBulk(productId, validUrls);
    return created;
  }
}

module.exports = CreateImageProduct;
