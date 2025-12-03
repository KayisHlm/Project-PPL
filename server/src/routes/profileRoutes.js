const express = require("express");
const router = express.Router();
const { authenticateToken } = require("../infrastructure/middleware/auth");

// Import controller
const GetUserProfileController = require("../infrastructure/controllers/profile/userProfileController");

// get /api/profile
router.get("/profile", authenticateToken, GetUserProfileController);

module.exports = router;