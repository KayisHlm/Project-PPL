const RejectSeller = require("../../../usecases/admin/rejectSeller");
const SellerRepository = require("../../repositories/sellerRepository");
const { NotFound } = require("../../../domain/errors");

async function RejectSellerController(req, res) {
    try {
        const { id } = req.params;
        console.log(`Admin rejecting seller ID: ${id}`);

        const sellerRepository = new SellerRepository();
        const rejectSeller = new RejectSeller(sellerRepository);

        const updatedSeller = await rejectSeller.execute(parseInt(id));

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