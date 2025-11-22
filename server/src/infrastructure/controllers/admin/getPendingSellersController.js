const GetPendingSellers = require("../../../usecases/admin/getPendingSellers"); // ⬅️ Fix: huruf P besar, tambah 's'
const SellerRepository = require("../../repositories/sellerRepository");

async function GetPendingSellersController(req, res) {
    try {
        console.log("Admin accessing pending sellers:", req.user);

        const sellerRepository = new SellerRepository();
        const getPendingSellers = new GetPendingSellers(sellerRepository);

        const pendingSellers = await getPendingSellers.execute();

        return res.status(200).json({
            code: 200,
            message: "Pending sellers retrieved successfully",
            data: {
                total: pendingSellers.length,
                sellers: pendingSellers
            }
        });

    } catch (error) {
        console.error("GetPendingSellers Controller Error:", error);
        
        return res.status(500).json({
            code: 500,
            message: "Internal server error while fetching pending sellers"
        });
    }
}

module.exports = GetPendingSellersController;