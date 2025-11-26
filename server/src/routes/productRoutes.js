const express = require("express");
const router = express.Router();
const { authenticateToken, requireSeller } = require("../infrastructure/middleware/auth");
const ProductController = require("../infrastructure/controllers/product/productController");

router.post("/", authenticateToken, requireSeller, ProductController.create);
router.get("/", authenticateToken, requireSeller, ProductController.list);
router.get("/categories", authenticateToken, requireSeller, ProductController.categories);

module.exports = router;
