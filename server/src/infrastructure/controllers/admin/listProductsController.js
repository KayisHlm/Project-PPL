const ProductRepository = require("../../repositories/productRepository");

module.exports = async function listProductsController(req, res){
  try {
    const repo = new ProductRepository();
    const rows = await repo.listAll();
    return res.status(200).json({ code: 200, data: rows });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}
