const express = require("express");
const router = express.Router();
const { authenticateToken, requireAdmin } = require("../infrastructure/middleware/auth");
const CategoryController = require("../infrastructure/controllers/category/categoryController");

// Create category (requires admin auth)
router.post("/", authenticateToken, requireAdmin, CategoryController.create);

// Get all categories
router.get("/", CategoryController.list);

// Get categories with count of products
router.get("/with-count", CategoryController.listWithCount);

// Get category by ID
router.get("/:id", CategoryController.getById);

// Update category by ID (requires admin auth)
router.put("/:id", authenticateToken, requireAdmin, CategoryController.update);

// Delete category by ID (requires admin auth)
router.delete("/:id", authenticateToken, requireAdmin, CategoryController.remove);

module.exports = router;
