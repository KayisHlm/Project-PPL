class GetShopsByProvince {
  constructor(statisticsRepository) {
    this.statisticsRepository = statisticsRepository;
  }

  async execute() {
    const data = await this.statisticsRepository.getShopsByProvince();
    return data;
  }
}

module.exports = GetShopsByProvince;
