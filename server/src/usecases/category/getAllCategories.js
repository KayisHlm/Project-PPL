class GetAllCategories {
  constructor(categoryRepository) {
    this.categoryRepository = categoryRepository;
  }

  async execute() {
    const categories = await this.categoryRepository.listAll();
    return categories;
  }
}

module.exports = GetAllCategories;
