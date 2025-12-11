const GetActiveSellers = require("../../../usecases/admin/getActiveSellers");
const SellerRepository = require("../../repositories/sellerRepository");

async function GetActiveSellersController(req, res) {
    try {
        console.log("Admin accessing active sellers:", req.user);

        const sellerRepository = new SellerRepository();
        const getActiveSellers = new GetActiveSellers(sellerRepository);

        const result = await getActiveSellers.execute();

        return res.status(200).json({
            code: 200,
            message: "Active sellers retrieved successfully",
            data: {
                total: result.total,
                sellers: result.sellers
            }
        });

    } catch (error) {
        console.error("GetActiveSellers Controller Error:", error);
        
        return res.status(500).json({
            code: 500,
            message: "Internal server error while fetching active sellers"
        });
    }
}

module.exports = GetActiveSellersController;
