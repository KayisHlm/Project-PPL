class GetReviewsRatingStats {
  constructor(statisticsRepository) {
    this.statisticsRepository = statisticsRepository;
  }

  async execute() {
    const data = await this.statisticsRepository.getReviewsRatingStats();
    return data;
  }
}

module.exports = GetReviewsRatingStats;
