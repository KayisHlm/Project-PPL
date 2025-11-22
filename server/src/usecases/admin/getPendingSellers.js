const PendingSellerInformation = require("../../dto/seller/pendingSellerInformation");

class GetPendingSellers {
    constructor(sellerRepository) {
        this.sellerRepository = sellerRepository;
    }

    async execute() {
        try {
            // Ambil semua seller dengan status pending
            const pendingSellers = await this.sellerRepository.findPendingSellers();

            // Format data menggunakan DTO
            const sellersInfo = pendingSellers.map(seller => 
                new PendingSellerInformation(seller)
            );

            return sellersInfo;

        } catch (error) {
            console.error("GetPendingSellers Use Case Error:", error);
            throw error;
        }
    }
}

module.exports = GetPendingSellers;