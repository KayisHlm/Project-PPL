const express = require("express");
const router = express.Router();
const { authenticateToken, authorizeRole } = require("../infrastructure/middleware/auth");

// Import controllers dari folder ADMIN (bukan seller)
const GetNonActiveSellersController = require("../infrastructure/controllers/admin/getNonActiveSellerController");
const GetActiveSellersController = require("../infrastructure/controllers/admin/getActiveSellerController");
const GetPendingSellersController = require("../infrastructure/controllers/admin/getPendingSellersController");
const GetApprovedSellersController = require("../infrastructure/controllers/admin/getApprovedSellerController");
const ApproveSellerController = require("../infrastructure/controllers/admin/approveSellerController");
const RejectSellerController = require("../infrastructure/controllers/admin/rejectSellerController");
const ListProductsController = require("../infrastructure/controllers/admin/listProductsController");

// Semua route admin harus authenticated dan role = 'platform_admin'
router.use(authenticateToken);
router.use(authorizeRole('platform_admin'));

// GET /api/admin/pending-sellers
router.get("/pending-sellers", GetPendingSellersController);

// GET /api/admin/approved-sellers
router.get("/approved-sellers", GetApprovedSellersController);

// POST /api/admin/sellers/:id/approve
router.post("/sellers/:id/approve", ApproveSellerController);

// POST /api/admin/sellers/:id/reject
router.post("/sellers/:id/reject", RejectSellerController);

// GET /api/admin/non-active-sellers
router.get("/non-active-sellers", GetNonActiveSellersController);

// GET /api/admin/active-sellers
router.get("/active-sellers", GetActiveSellersController);

// GET /api/admin/products
router.get("/products", ListProductsController);

module.exports = router;
