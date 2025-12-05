const express = require("express");
const router = express.Router();
const { authenticateToken, requireAdmin } = require("../infrastructure/middleware/auth");
const { getProductsByCategory } = require("../infrastructure/controllers/statistics/getProductsByCategoryController");
const { getShopsByProvince } = require("../infrastructure/controllers/statistics/getShopsByProvinceController");
const { getSellersActiveStatus } = require("../infrastructure/controllers/statistics/getSellersActiveStatusController");
const { getReviewsRatingStats } = require("../infrastructure/controllers/statistics/getReviewsRatingStatsController");

// All statistics endpoints require admin authentication
router.get("/products-by-category", authenticateToken, requireAdmin, getProductsByCategory);
router.get("/shops-by-province", authenticateToken, requireAdmin, getShopsByProvince);
router.get("/sellers-active-status", authenticateToken, requireAdmin, getSellersActiveStatus);
router.get("/reviews-rating-stats", authenticateToken, requireAdmin, getReviewsRatingStats);

module.exports = router;
