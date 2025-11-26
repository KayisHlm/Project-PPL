const { BadRequest } = require("../../domain/errors");

class CreateProduct {
  constructor(productRepository) {
    this.productRepository = productRepository;
  }

  async execute(sellerId, body) {
    const required = ["name","condition","price","weight","minOrder","category","warranty","year","claim","description"];
    for (const f of required) {
      if (!body[f] && body[f] !== 0) throw new BadRequest(`Field '${f}' is required`);
    }
    if (!["Baru","Bekas"].includes(body.condition)) throw new BadRequest("condition must be 'Baru' or 'Bekas'");
    const price = parseInt(body.price,10);
    const weight = parseInt(body.weight,10);
    const minOrder = parseInt(body.minOrder,10);
    const year = parseInt(body.year,10);
    if (!(price > 0)) throw new BadRequest("price must be > 0");
    if (!(weight >= 1)) throw new BadRequest("weight must be >= 1");
    if (!(minOrder >= 1)) throw new BadRequest("minOrder must be >= 1");
    if (!(year >= 2000)) throw new BadRequest("year must be >= 2000");

    const created = await this.productRepository.create(sellerId, body);
    return created;
  }
}

module.exports = CreateProduct;
