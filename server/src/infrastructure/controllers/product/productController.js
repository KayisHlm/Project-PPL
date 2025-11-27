const CreateProduct = require("../../../usecases/product/createProduct");
const ProductRepository = require("../../repositories/productRepository");
const ProductInformation = require("../../../dto/product/productInformation");

// Helper function to map database row to camelCase
function mapProductToDTO(product) {
  if (!product) return null;
  return {
    id: product.id,
    sellerId: product.seller_id,
    name: product.name,
    price: parseFloat(product.price),
    weight: product.weight,
    stock: product.stock,
    category: product.category,
    description: product.description,
    rating: parseFloat(product.rating || 0),
    createdAt: product.created_at,
    updatedAt: product.updated_at
  };
}

async function create(req, res) {
  try {
    const sellerId = req.user && req.user.sellerId ? req.user.sellerId : null;
    if (!sellerId) {
      return res.status(400).json({ code: 400, message: "sellerId not found" });
    }
    const usecase = new CreateProduct(new ProductRepository());
    const product = await usecase.execute(sellerId, req.body);
    const productDTO = mapProductToDTO(product);
    return res.status(201).json({ code: 201, message: "Product created", product: productDTO });
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
    const productsDTO = items.map(mapProductToDTO);
    return res.status(200).json({ code: 200, data: productsDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function listAll(req, res) {
  try {
    const repo = new ProductRepository();
    const items = await repo.listAll();
    const productsDTO = items.map(mapProductToDTO);
    return res.status(200).json({ code: 200, data: productsDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function getById(req, res) {
  try {
    const productId = req.params.id;
    if (!productId) {
      return res.status(400).json({ code: 400, message: "Product ID is required" });
    }
    const repo = new ProductRepository();
    const product = await repo.findById(productId);
    if (!product) {
      return res.status(404).json({ code: 404, message: "Product not found" });
    }
    const productDTO = mapProductToDTO(product);
    return res.status(200).json({ code: 200, data: productDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function update(req, res) {
  try {
    const productId = req.params.id;
    const sellerId = req.user && req.user.sellerId ? req.user.sellerId : null;
    if (!sellerId) {
      return res.status(400).json({ code: 400, message: "sellerId not found" });
    }
    if (!productId) {
      return res.status(400).json({ code: 400, message: "Product ID is required" });
    }
    
    // Check if product exists and belongs to seller
    const repo = new ProductRepository();
    const existingProduct = await repo.findById(productId);
    if (!existingProduct) {
      return res.status(404).json({ code: 404, message: "Product not found" });
    }
    if (existingProduct.seller_id !== sellerId) {
      return res.status(403).json({ code: 403, message: "Forbidden: You can only update your own products" });
    }

    const updated = await repo.update(productId, req.body);
    const productDTO = mapProductToDTO(updated);
    return res.status(200).json({ code: 200, message: "Product updated", product: productDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function remove(req, res) {
  try {
    const productId = req.params.id;
    const sellerId = req.user && req.user.sellerId ? req.user.sellerId : null;
    if (!sellerId) {
      return res.status(400).json({ code: 400, message: "sellerId not found" });
    }
    if (!productId) {
      return res.status(400).json({ code: 400, message: "Product ID is required" });
    }
    
    // Check if product exists and belongs to seller
    const repo = new ProductRepository();
    const existingProduct = await repo.findById(productId);
    if (!existingProduct) {
      return res.status(404).json({ code: 404, message: "Product not found" });
    }
    if (existingProduct.seller_id !== sellerId) {
      return res.status(403).json({ code: 403, message: "Forbidden: You can only delete your own products" });
    }

    await repo.delete(productId);
    return res.status(200).json({ code: 200, message: "Product deleted successfully" });
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
    const items = await repo.categoriesBySeller(sellerId);
    return res.status(200).json({ code: 200, data: items });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

module.exports = { create, list, listAll, getById, update, remove, categories };
