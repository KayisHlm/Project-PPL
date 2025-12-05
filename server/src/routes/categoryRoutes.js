const express = require("express");
const router = express.Router();
const { authenticateToken, requireAdmin } = require("../infrastructure/middleware/auth");
const CreateCategoryController = require("../infrastructure/controllers/category/createCategoryController");
const GetAllCategoriesController = require("../infrastructure/controllers/category/getAllCategoriesController");

// Create category (requires admin auth)
router.post("/", authenticateToken, requireAdmin, CreateCategoryController.create);

// Get all categories
router.get("/", GetAllCategoriesController.getAll);

module.exports = router;
