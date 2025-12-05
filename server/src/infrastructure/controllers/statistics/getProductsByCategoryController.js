const GetProductsByCategory = require("../../../usecases/statistics/getProductsByCategory");
const StatisticsRepository = require("../../repositories/statisticsRepository");

async function getProductsByCategory(req, res) {
  try {
    const usecase = new GetProductsByCategory(new StatisticsRepository());
    const data = await usecase.execute();
    
    return res.status(200).json({ 
      code: 200, 
      data 
    });
  } catch (error) {
    return res.status(500).json({ 
      code: 500, 
      message: "Internal Server Error", 
      error: error.message 
    });
  }
}

module.exports = { getProductsByCategory };
