const CreateCategory = require("../../../usecases/category/createCategory");
const CategoryRepository = require("../../repositories/categoryRepository");
const CategoryInformation = require("../../../dto/category/categoryInformation");

async function create(req, res) {
  try {
    const usecase = new CreateCategory(new CategoryRepository());
    const result = await usecase.execute(req.body);
    
    if (result && result.exists) {
      const categoryDTO = new CategoryInformation(result.category);
      return res.status(200).json({ 
        code: 200, 
        message: "Category already exists", 
        category: categoryDTO 
      });
    }
    
    const categoryDTO = new CategoryInformation(result);
    return res.status(201).json({ 
      code: 201, 
      message: "Category created", 
      category: categoryDTO 
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

module.exports = { create };
