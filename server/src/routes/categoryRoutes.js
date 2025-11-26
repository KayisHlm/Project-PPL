const express = require("express");
const router = express.Router();
const { authenticateToken, requireAdmin } = require("../infrastructure/middleware/auth");
const CategoryController = require("../infrastructure/controllers/category/categoryController");

router.post("/", authenticateToken, requireAdmin, CategoryController.create);
router.get("/", authenticateToken, requireAdmin, CategoryController.list);
router.get("/with-count", authenticateToken, requireAdmin, CategoryController.listWithCount);
// Public listing for authenticated users (seller can read categories)
router.get("/public", authenticateToken, CategoryController.publicList);

module.exports = router;
