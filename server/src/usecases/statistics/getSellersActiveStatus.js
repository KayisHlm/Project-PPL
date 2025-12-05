class GetSellersActiveStatus {
  constructor(statisticsRepository) {
    this.statisticsRepository = statisticsRepository;
  }

  async execute() {
    const data = await this.statisticsRepository.getSellersActiveStatus();
    return data;
  }
}

module.exports = GetSellersActiveStatus;
