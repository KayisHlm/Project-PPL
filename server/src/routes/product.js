const express = require("express");
const router = express.Router();
const { authenticateToken, requireSeller } = require("../infrastructure/middleware/auth");
const CreateProductWithImageController = require("../infrastructure/controllers/product/createProductWithImageController");
const GetAllProductsController = require("../infrastructure/controllers/product/getAllProductsController");
const GetProductByIdController = require("../infrastructure/controllers/product/getProductByIdController");

// Create product with images (requires seller auth)
router.post("/", authenticateToken, requireSeller, CreateProductWithImageController.create);

// Get all products (public)
router.get("/", GetAllProductsController.getAll);

// Get product by ID (public)
router.get("/:id", GetProductByIdController.getById);

module.exports = router;
