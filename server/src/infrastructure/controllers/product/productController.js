const CreateProduct = require("../../../usecases/product/createProduct");
const ProductRepository = require("../../repositories/productRepository");

async function create(req, res) {
  try {
    const sellerId = req.user && req.user.sellerId ? req.user.sellerId : null;
    if (!sellerId) {
      return res.status(400).json({ code: 400, message: "sellerId not found" });
    }
    const usecase = new CreateProduct(new ProductRepository());
    const product = await usecase.execute(sellerId, req.body);
    return res.status(201).json({ code: 201, message: "Product created", product });
  } catch (error) {
    if (error.statusCode) {
      return res.status(error.statusCode).json({ code: error.statusCode, message: error.message });
    }
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function list(req, res) {
  try {
    const sellerId = req.user && req.user.sellerId ? req.user.sellerId : null;
    if (!sellerId) {
      return res.status(400).json({ code: 400, message: "sellerId not found" });
    }
    const repo = new ProductRepository();
    const items = await repo.listBySeller(sellerId);
    return res.status(200).json({ code: 200, data: items });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function categories(req, res) {
  try {
    const sellerId = req.user && req.user.sellerId ? req.user.sellerId : null;
    if (!sellerId) {
      return res.status(400).json({ code: 400, message: "sellerId not found" });
    }
    const repo = new ProductRepository();
    const rows = await repo.categoriesBySeller(sellerId);
    return res.status(200).json({ code: 200, data: rows });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

module.exports = { create, list, categories };
