const express = require("express");
const router = express.Router();
const { authenticateToken, authorizeRole } = require("../infrastructure/middleware/auth");
const GetPendingSellersController = require("../infrastructure/controllers/seller/getPendingSellersController");
const ApproveSellerController = require("../infrastructure/controllers/seller/approveSellerController");
const RejectSellerController = require("../infrastructure/controllers/seller/rejectSellerController");

// Semua route admin harus authenticated dan role = 'admin'
router.use(authenticateToken);
router.use(authorizeRole('admin'));

// GET /api/admin/pending-sellers
router.get("/pending-sellers", GetPendingSellersController);

// POST /api/admin/sellers/:id/approve
router.post("/sellers/:id/approve", ApproveSellerController);

// POST /api/admin/sellers/:id/reject
router.post("/sellers/:id/reject", RejectSellerController);

module.exports = router;