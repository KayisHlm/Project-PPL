const CreateCategory = require("../../../usecases/category/createCategory");
const CategoryRepository = require("../../repositories/categoryRepository");
const CategoryInformation = require("../../../dto/categori/categoryInformation");

// Helper function to map database row to camelCase
function mapCategoryToDTO(category) {
  if (!category) return null;
  return {
    id: category.id,
    name: category.name,
    description: category.description,
    createdAt: category.created_at,
    updatedAt: category.updated_at
  };
}

async function create(req, res) {
  try {
    const usecase = new CreateCategory(new CategoryRepository());
    const result = await usecase.execute(req.body);
    
    if (result && result.exists) {
      const categoryDTO = mapCategoryToDTO(result.category);
      return res.status(200).json({ 
        code: 200, 
        message: "Category already exists", 
        category: categoryDTO 
      });
    }
    
    const categoryDTO = mapCategoryToDTO(result);
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

async function list(req, res) {
  try {
    const repo = new CategoryRepository();
    const categories = await repo.listAll();
    const categoriesDTO = categories.map(mapCategoryToDTO);
    return res.status(200).json({ code: 200, data: categoriesDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function listWithCount(req, res) {
  try {
    const repo = new CategoryRepository();
    const categories = await repo.listWithCount();
    const categoriesDTO = categories.map(cat => ({
      ...mapCategoryToDTO(cat),
      productCount: parseInt(cat.product_count || 0, 10)
    }));
    return res.status(200).json({ code: 200, data: categoriesDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function getById(req, res) {
  try {
    const categoryId = req.params.id;
    if (!categoryId) {
      return res.status(400).json({ code: 400, message: "Category ID is required" });
    }

    const repo = new CategoryRepository();
    const category = await repo.findById(categoryId);
    if (!category) {
      return res.status(404).json({ code: 404, message: "Category not found" });
    }

    const categoryDTO = mapCategoryToDTO(category);
    return res.status(200).json({ code: 200, data: categoryDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function update(req, res) {
  try {
    const categoryId = req.params.id;
    if (!categoryId) {
      return res.status(400).json({ code: 400, message: "Category ID is required" });
    }

    const repo = new CategoryRepository();
    const existingCategory = await repo.findById(categoryId);
    if (!existingCategory) {
      return res.status(404).json({ code: 404, message: "Category not found" });
    }

    // Check if new name already exists (if name is being changed)
    if (req.body.name && req.body.name !== existingCategory.name) {
      const duplicate = await repo.findByName(req.body.name);
      if (duplicate) {
        return res.status(400).json({ code: 400, message: "Category name already exists" });
      }
    }

    const updated = await repo.update(categoryId, req.body);
    const categoryDTO = mapCategoryToDTO(updated);
    return res.status(200).json({ code: 200, message: "Category updated", category: categoryDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function remove(req, res) {
  try {
    const categoryId = req.params.id;
    if (!categoryId) {
      return res.status(400).json({ code: 400, message: "Category ID is required" });
    }

    const repo = new CategoryRepository();
    const existingCategory = await repo.findById(categoryId);
    if (!existingCategory) {
      return res.status(404).json({ code: 404, message: "Category not found" });
    }

    // Check if category has products
    const productCount = await repo.countProducts(categoryId);
    if (productCount > 0) {
      return res.status(400).json({ 
        code: 400, 
        message: `Cannot delete category with ${productCount} product(s). Please reassign or delete products first.` 
      });
    }

    await repo.delete(categoryId);
    return res.status(200).json({ code: 200, message: "Category deleted successfully" });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

module.exports = { create, list, listWithCount, getById, update, remove };
