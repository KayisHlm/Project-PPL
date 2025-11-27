const { BadRequest } = require("../../domain/errors");

class CreateCategory {
  constructor(categoryRepository) {
    this.categoryRepository = categoryRepository;
  }

  async execute(body) {
    // Validate required fields
    if (!body.name) {
      throw new BadRequest("Field 'name' is required");
    }

    const name = (body.name || "").trim();
    if (name.length < 2) {
      throw new BadRequest("Category name must be at least 2 characters");
    }
    if (name.length > 100) {
      throw new BadRequest("Category name must not exceed 100 characters");
    }

    // Optional description validation
    const description = body.description ? body.description.trim() : null;

    // Check if category already exists
    const existing = await this.categoryRepository.findByName(name);
    if (existing) {
      return { exists: true, name, category: existing };
    }

    // Create new category
    const created = await this.categoryRepository.create(name, description);
    return created;
  }
}

module.exports = CreateCategory;
