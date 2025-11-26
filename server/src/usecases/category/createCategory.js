const { BadRequest } = require("../../domain/errors");

class CreateCategory {
  constructor(categoryRepository) {
    this.categoryRepository = categoryRepository;
  }

  async execute(sellerId, name) {
    const v = (name || "").trim();
    if (v.length < 2) throw new BadRequest("Category name must be at least 2 characters");
    const created = await this.categoryRepository.createGlobal(v);
    if (!created) return { exists: true, name: v };
    return created;
  }
}

module.exports = CreateCategory;
