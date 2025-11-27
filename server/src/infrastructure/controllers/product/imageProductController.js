const CreateImageProduct = require("../../../usecases/product/createImageProduct");
const ImageRepository = require("../../repositories/imageRepository");
const ProductRepository = require("../../repositories/productRepository");
const ImageInformation = require("../../../dto/imageProduct/imageInformation");

// Helper function to map database row to camelCase
function mapImageToDTO(image) {
  if (!image) return null;
  return {
    id: image.id,
    productId: image.product_id,
    imageUrl: image.image_url,
    createdAt: image.created_at,
    updatedAt: image.updated_at
  };
}

async function create(req, res) {
  try {
    const productId = req.params.productId;
    if (!productId) {
      return res.status(400).json({ code: 400, message: "Product ID is required" });
    }

    // Check if user is authorized (seller must own the product)
    const sellerId = req.user && req.user.sellerId ? req.user.sellerId : null;
    if (!sellerId) {
      return res.status(401).json({ code: 401, message: "Unauthorized: Seller ID required" });
    }

    const productRepo = new ProductRepository();
    const product = await productRepo.findById(productId);
    if (!product) {
      return res.status(404).json({ code: 404, message: "Product not found" });
    }

    if (product.seller_id !== sellerId) {
      return res.status(403).json({ code: 403, message: "Forbidden: You can only add images to your own products" });
    }

    const usecase = new CreateImageProduct(new ImageRepository(), productRepo);
    const image = await usecase.execute(productId, req.body);
    const imageDTO = mapImageToDTO(image);
    return res.status(201).json({ code: 201, message: "Image created", image: imageDTO });
  } catch (error) {
    if (error.statusCode) {
      return res.status(error.statusCode).json({ code: error.statusCode, message: error.message });
    }
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function createBulk(req, res) {
  try {
    const productId = req.params.productId;
    if (!productId) {
      return res.status(400).json({ code: 400, message: "Product ID is required" });
    }

    // Check if user is authorized (seller must own the product)
    const sellerId = req.user && req.user.sellerId ? req.user.sellerId : null;
    if (!sellerId) {
      return res.status(401).json({ code: 401, message: "Unauthorized: Seller ID required" });
    }

    const productRepo = new ProductRepository();
    const product = await productRepo.findById(productId);
    if (!product) {
      return res.status(404).json({ code: 404, message: "Product not found" });
    }

    if (product.seller_id !== sellerId) {
      return res.status(403).json({ code: 403, message: "Forbidden: You can only add images to your own products" });
    }

    const usecase = new CreateImageProduct(new ImageRepository(), productRepo);
    const images = await usecase.executeBulk(productId, req.body);
    const imagesDTO = images.map(mapImageToDTO);
    return res.status(201).json({ code: 201, message: "Images created", images: imagesDTO });
  } catch (error) {
    if (error.statusCode) {
      return res.status(error.statusCode).json({ code: error.statusCode, message: error.message });
    }
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function listByProduct(req, res) {
  try {
    const productId = req.params.productId;
    if (!productId) {
      return res.status(400).json({ code: 400, message: "Product ID is required" });
    }

    const repo = new ImageRepository();
    const images = await repo.listByProduct(productId);
    const imagesDTO = images.map(mapImageToDTO);
    return res.status(200).json({ code: 200, data: imagesDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function listAll(req, res) {
  try {
    const repo = new ImageRepository();
    const images = await repo.listAll();
    const imagesDTO = images.map(mapImageToDTO);
    return res.status(200).json({ code: 200, data: imagesDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function getById(req, res) {
  try {
    const imageId = req.params.id;
    if (!imageId) {
      return res.status(400).json({ code: 400, message: "Image ID is required" });
    }

    const repo = new ImageRepository();
    const image = await repo.findById(imageId);
    if (!image) {
      return res.status(404).json({ code: 404, message: "Image not found" });
    }

    const imageDTO = mapImageToDTO(image);
    return res.status(200).json({ code: 200, data: imageDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function update(req, res) {
  try {
    const imageId = req.params.id;
    if (!imageId) {
      return res.status(400).json({ code: 400, message: "Image ID is required" });
    }

    // Check if user is authorized
    const sellerId = req.user && req.user.sellerId ? req.user.sellerId : null;
    if (!sellerId) {
      return res.status(401).json({ code: 401, message: "Unauthorized: Seller ID required" });
    }

    const imageRepo = new ImageRepository();
    const existingImage = await imageRepo.findById(imageId);
    if (!existingImage) {
      return res.status(404).json({ code: 404, message: "Image not found" });
    }

    // Check if seller owns the product
    const productRepo = new ProductRepository();
    const product = await productRepo.findById(existingImage.product_id);
    if (!product) {
      return res.status(404).json({ code: 404, message: "Product not found" });
    }

    if (product.seller_id !== sellerId) {
      return res.status(403).json({ code: 403, message: "Forbidden: You can only update images of your own products" });
    }

    // Validate image_url
    const imageUrl = req.body.image_url || req.body.imageUrl;
    if (!imageUrl) {
      return res.status(400).json({ code: 400, message: "image_url is required" });
    }

    const updated = await imageRepo.update(imageId, imageUrl);
    const imageDTO = mapImageToDTO(updated);
    return res.status(200).json({ code: 200, message: "Image updated", image: imageDTO });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function remove(req, res) {
  try {
    const imageId = req.params.id;
    if (!imageId) {
      return res.status(400).json({ code: 400, message: "Image ID is required" });
    }

    // Check if user is authorized
    const sellerId = req.user && req.user.sellerId ? req.user.sellerId : null;
    if (!sellerId) {
      return res.status(401).json({ code: 401, message: "Unauthorized: Seller ID required" });
    }

    const imageRepo = new ImageRepository();
    const existingImage = await imageRepo.findById(imageId);
    if (!existingImage) {
      return res.status(404).json({ code: 404, message: "Image not found" });
    }

    // Check if seller owns the product
    const productRepo = new ProductRepository();
    const product = await productRepo.findById(existingImage.product_id);
    if (!product) {
      return res.status(404).json({ code: 404, message: "Product not found" });
    }

    if (product.seller_id !== sellerId) {
      return res.status(403).json({ code: 403, message: "Forbidden: You can only delete images of your own products" });
    }

    await imageRepo.delete(imageId);
    return res.status(200).json({ code: 200, message: "Image deleted successfully" });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

async function removeByProduct(req, res) {
  try {
    const productId = req.params.productId;
    if (!productId) {
      return res.status(400).json({ code: 400, message: "Product ID is required" });
    }

    // Check if user is authorized
    const sellerId = req.user && req.user.sellerId ? req.user.sellerId : null;
    if (!sellerId) {
      return res.status(401).json({ code: 401, message: "Unauthorized: Seller ID required" });
    }

    // Check if seller owns the product
    const productRepo = new ProductRepository();
    const product = await productRepo.findById(productId);
    if (!product) {
      return res.status(404).json({ code: 404, message: "Product not found" });
    }

    if (product.seller_id !== sellerId) {
      return res.status(403).json({ code: 403, message: "Forbidden: You can only delete images of your own products" });
    }

    const imageRepo = new ImageRepository();
    const deleted = await imageRepo.deleteByProduct(productId);
    return res.status(200).json({ 
      code: 200, 
      message: `${deleted.length} image(s) deleted successfully`,
      count: deleted.length
    });
  } catch (error) {
    return res.status(500).json({ code: 500, message: "Internal Server Error", error: error.message });
  }
}

module.exports = { 
  create, 
  createBulk, 
  listByProduct, 
  listAll, 
  getById, 
  update, 
  remove, 
  removeByProduct 
};
