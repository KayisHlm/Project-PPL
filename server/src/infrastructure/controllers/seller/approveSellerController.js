const ApproveSeller = require("../../../usecases/seller/approveSeller");
const SellerRepository = require("../../repositories/sellerRepository");
const { NotFound } = require("../../../domain/errors");

async function ApproveSellerController(req, res) {
    try {
        const { id } = req.params;
        console.log(`✅ Admin approving seller ID: ${id}`);

        const sellerRepository = new SellerRepository();
        const approveSeller = new ApproveSeller(sellerRepository);

        const updatedSeller = await approveSeller.execute(parseInt(id));

        return res.status(200).json({
            code: 200,
            message: "Seller approved successfully",
            data: updatedSeller
        });

    } catch (error) {
        console.error("❌ ApproveSeller Controller Error:", error);

        if (error instanceof NotFound) {
            return res.status(404).json({
                code: 404,
                message: error.message
            });
        }
        
        return res.status(500).json({
            code: 500,
            message: "Internal server error while approving seller"
        });
    }
}

module.exports = ApproveSellerController;