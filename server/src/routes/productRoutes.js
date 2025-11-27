const express = require("express");
const router = express.Router();
const { authenticateToken, requireSeller } = require("../infrastructure/middleware/auth");
const ProductController = require("../infrastructure/controllers/product/productController");

// Create product (requires seller auth)
router.post("/", authenticateToken, requireSeller, ProductController.create);
// Get products for the authenticated seller
router.get("/", authenticateToken, requireSeller, ProductController.list);
// Get all products (public)
router.get("/all", ProductController.listAll);
// Get product categories for the authenticated seller
router.get("/categories", authenticateToken, requireSeller, ProductController.categories);
// Get product by ID (public)
router.get("/:id", ProductController.getById);
// Update product by ID (requires seller auth)
router.put("/:id", authenticateToken, requireSeller, ProductController.update);
// Delete product by ID (requires seller auth)
router.delete("/:id", authenticateToken, requireSeller, ProductController.remove);

module.exports = router;
