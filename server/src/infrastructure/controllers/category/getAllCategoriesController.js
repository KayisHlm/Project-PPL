const GetAllCategories = require("../../../usecases/category/getAllCategories");
const CategoryRepository = require("../../repositories/categoryRepository");
const CategoryInformation = require("../../../dto/category/categoryInformation");

async function getAll(req, res) {
  try {
    const usecase = new GetAllCategories(new CategoryRepository());
    const categories = await usecase.execute();
    
    const categoriesDTO = categories.map(category => new CategoryInformation(category));
    
    return res.status(200).json({ 
      code: 200, 
      data: categoriesDTO 
    });
  } catch (error) {
    if (error.statusCode) {
      return res.status(error.statusCode).json({ 
        code: error.statusCode, 
        message: error.message 
      });
    }
    return res.status(500).json({ 
      code: 500, 
      message: "Internal Server Error", 
      error: error.message 
    });
  }
}

module.exports = { getAll };
