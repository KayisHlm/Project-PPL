const GetShopsByProvince = require("../../../usecases/statistics/getShopsByProvince");
const StatisticsRepository = require("../../repositories/statisticsRepository");

async function getShopsByProvince(req, res) {
  try {
    const usecase = new GetShopsByProvince(new StatisticsRepository());
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

module.exports = { getShopsByProvince };
