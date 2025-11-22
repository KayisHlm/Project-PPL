const express = require("express");
const router = express.Router();
const { authenticateToken, authorizeRole } = require("../infrastructure/middleware/auth");

// Import controllers dari folder ADMIN (bukan seller)
const GetPendingSellersController = require("../infrastructure/controllers/admin/getPendingSellersController");
const ApproveSellerController = require("../infrastructure/controllers/admin/approveSellerController");
const RejectSellerController = require("../infrastructure/controllers/admin/rejectSellerController");

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