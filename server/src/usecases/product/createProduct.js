const { BadRequest } = require("../../domain/errors");

class CreateProduct {
  constructor(productRepository) {
    this.productRepository = productRepository;
  }

  async execute(sellerId, body) {
    // Validate required fields based on actual database schema
    const required = ["name", "price", "weight", "stock"];
    for (const f of required) {
      if (!body[f] && body[f] !== 0) {
        throw new BadRequest(`Field '${f}' is required`);
      }
    }

    // Validate data types and ranges
    const price = parseFloat(body.price);
    const weight = parseInt(body.weight, 10);
    const stock = parseInt(body.stock, 10);

    if (isNaN(price) || price <= 0) {
      throw new BadRequest("price must be a positive number");
    }
    if (isNaN(weight) || weight < 1) {
      throw new BadRequest("weight must be >= 1");
    }
    if (isNaN(stock) || stock < 0) {
      throw new BadRequest("stock must be >= 0");
    }

    // Optional fields validation
    if (body.category && typeof body.category !== 'string') {
      throw new BadRequest("category must be a string");
    }
    if (body.description && typeof body.description !== 'string') {
      throw new BadRequest("description must be a string");
    }

    // Prepare data for repository
    const productData = {
      name: body.name,
      price,
      weight,
      stock,
      category: body.category || null,
      description: body.description || null
    };

    const created = await this.productRepository.create(sellerId, productData);
    return created;
  }
}

module.exports = CreateProduct;
