const RejectSeller = require("../../../usecases/admin/rejectSeller");
const SellerRepository = require("../../repositories/sellerRepository");
const mailer = require("../../services/mailer");
const { NotFound } = require("../../../domain/errors");

async function RejectSellerController(req, res) {
    try {
        const { id } = req.params;
        const { reason } = req.body || {};

        if (!reason || !reason.trim()) {
            return res.status(400).json({
                code: 400,
                message: "Rejection reason is required"
            });
        }
        console.log(`Admin rejecting seller ID: ${id}`);

        const sellerRepository = new SellerRepository();
        const rejectSeller = new RejectSeller(sellerRepository, mailer);

        const updatedSeller = await rejectSeller.execute(parseInt(id), reason.trim());

        return res.status(200).json({
            code: 200,
            message: "Seller rejected successfully",
            data: updatedSeller
        });

    } catch (error) {
        console.error("RejectSeller Controller Error:", error);

        if (error instanceof NotFound) {
            return res.status(404).json({
                code: 404,
                message: error.message
            });
        }
        
        return res.status(500).json({
            code: 500,
            message: "Internal server error while rejecting seller"
        });
    }
}

module.exports = RejectSellerController;