const express = require("express");
const router = express.Router();
const CreateReviewController = require("../infrastructure/controllers/review/createReviewController");
const GetReviewsByProductController = require("../infrastructure/controllers/review/getReviewsByProductController");
const GetProductReviewStatsController = require("../infrastructure/controllers/review/getProductReviewStatsController");
const GetReviewByIdController = require("../infrastructure/controllers/review/getReviewByIdController");

// Create review for a product (public)
router.post("/products/:productId", CreateReviewController.create);

// Get all reviews for a product (public)
router.get("/products/:productId", GetReviewsByProductController.listByProduct);

// Get product rating statistics (public)
router.get("/products/:productId/stats", GetProductReviewStatsController.getProductStats);

// Get review by ID (public)
router.get("/:id", GetReviewByIdController.getById);

module.exports = router;
