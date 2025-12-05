class GetProductsByCategory {
  constructor(statisticsRepository) {
    this.statisticsRepository = statisticsRepository;
  }

  async execute() {
    const data = await this.statisticsRepository.getProductsByCategory();
    return data;
  }
}

module.exports = GetProductsByCategory;
