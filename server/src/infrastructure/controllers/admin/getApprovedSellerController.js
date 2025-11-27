const GetApprovedSellers = require("../../../usecases/admin/getApprovedSellers");
const SellerRepository = require("../../repositories/sellerRepository");

async function GetApprovedSellersController(req, res) {
    try {
        console.log("Admin accessing approved sellers:", req.user);

        const sellerRepository = new SellerRepository();
        const getApprovedSellers = new GetApprovedSellers(sellerRepository);

        const result = await getApprovedSellers.execute();

        return res.status(200).json({
            code: 200,
            message: "Approved sellers retrieved successfully",
            data: {
                total: result.total,
                sellers: result.sellers
            }
        });

    } catch (error) {
        console.error("GetApprovedSellers Controller Error:", error);
        
        return res.status(500).json({
            code: 500,
            message: "Internal server error while fetching approved sellers"
        });
    }
}

module.exports = GetApprovedSellersController;