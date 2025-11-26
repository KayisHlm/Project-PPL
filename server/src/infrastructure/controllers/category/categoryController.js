const CreateCategory = require("../../../usecases/category/createCategory");
const CategoryRepository = require("../../repositories/categoryRepository");

async function create(req, res) {
  try {
    const name = req.body && req.body.name;
    const usecase = new CreateCategory(new CategoryRepository());
    const cat = await usecase.execute(null, name);
    if (cat && cat.exists) return res.status(200).json({ code: 200, message: "Category exists", category: cat });
    return res.status(201).json({ code: 201, message: "Category created", category: cat });
  } catch (error) {
    if (error.statusCode) return res.status(error.statusCode).json({ code: error.statusCode, message: error.message });
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function list(req, res) {
  try {
    const repo = new CategoryRepository();
    const rows = await repo.listAll();
    return res.status(200).json({ code: 200, data: rows });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function listWithCount(req, res) {
  try {
    const repo = new CategoryRepository();
    const rows = await repo.listWithCountAll();
    return res.status(200).json({ code: 200, data: rows });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

module.exports = { create, list, listWithCount };
