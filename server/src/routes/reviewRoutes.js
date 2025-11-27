const express = require("express");
const router = express.Router();
const { authenticateToken } = require("../infrastructure/middleware/auth");
const ReviewController = require("../infrastructure/controllers/review/reviewController");

// Create review for a product
router.post("/products/:productId", ReviewController.create);

// Get all reviews for a product (public)
router.get("/products/:productId", ReviewController.listByProduct);

// Get product rating statistics (public)
router.get("/products/:productId/stats", ReviewController.getProductStats);

// Get all reviews by email (query parameter)
router.get("/by-email", ReviewController.listByEmail);

// Get all reviews (public)
router.get("/", ReviewController.listAll);

// Get review by ID (public)
router.get("/:id", ReviewController.getById);

// Update review (requires authentication by email match)
router.put("/:id", authenticateToken, ReviewController.update);

// Delete review (requires authentication by email match)
router.delete("/:id", authenticateToken, ReviewController.remove);

module.exports = router;
