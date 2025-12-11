const GetSellersByProvince = require("../../../usecases/admin/getSellersByProvince");
const SellerRepository = require("../../repositories/sellerRepository");

async function GetSellersByProvinceController(req, res) {
    try {
        console.log("Admin accessing sellers by province:", req.user);

        const sellerRepository = new SellerRepository();
        const getSellersByProvince = new GetSellersByProvince(sellerRepository);

        const result = await getSellersByProvince.execute();

        return res.status(200).json({
            code: 200,
            message: "Sellers by province retrieved successfully",
            data: {
                total: result.total,
                sellers: result.sellers
            }
        });

    } catch (error) {
        console.error("GetSellersByProvince Controller Error:", error);
        
        return res.status(500).json({
            code: 500,
            message: "Internal server error while fetching sellers by province"
        });
    }
}

module.exports = GetSellersByProvinceController;
