const express = require("express");
const router = express.Router();
const { authenticateToken, requireSeller } = require("../infrastructure/middleware/auth");
const ImageController = require("../infrastructure/controllers/product/imageProductController");

// Create single image for a product (requires seller auth)
router.post("/products/:productId", authenticateToken, requireSeller, ImageController.create);

// Create multiple images for a product (requires seller auth)
router.post("/products/:productId/bulk", authenticateToken, requireSeller, ImageController.createBulk);

// Get all images for a product (public)
router.get("/products/:productId", ImageController.listByProduct);

// Delete all images for a product (requires seller auth)
router.delete("/products/:productId", authenticateToken, requireSeller, ImageController.removeByProduct);

// Get all images (public)
router.get("/", ImageController.listAll);

// Get image by ID (public)
router.get("/:id", ImageController.getById);

// Update image (requires seller auth)
router.put("/:id", authenticateToken, requireSeller, ImageController.update);

// Delete image (requires seller auth)
router.delete("/:id", authenticateToken, requireSeller, ImageController.remove);

module.exports = router;
