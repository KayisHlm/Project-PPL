const GetNonActiveSellers = require("../../../usecases/admin/getNonActiveSellers");
const SellerRepository = require("../../repositories/sellerRepository");

async function GetNonActiveSellersController(req, res) {
    try {
        console.log("Admin accessing non-active sellers:", req.user);

        const sellerRepository = new SellerRepository();
        const getNonActiveSellers = new GetNonActiveSellers(sellerRepository);

        const result = await getNonActiveSellers.execute();

        return res.status(200).json({
            code: 200,
            message: "Non-active sellers retrieved successfully",
            data: {
                total: result.total,
                sellers: result.sellers
            }
        });

    } catch (error) {
        console.error("GetNonActiveSellers Controller Error:", error);
        
        return res.status(500).json({
            code: 500,
            message: "Internal server error while fetching non-active sellers"
        });
    }
}

module.exports = GetNonActiveSellersController;
