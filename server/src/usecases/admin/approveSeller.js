const { NotFound } = require("../../domain/errors");

class ApproveSeller {
    constructor(sellerRepository) {
        this.sellerRepository = sellerRepository;
    }

    async execute(sellerId) {
        try {
            // Cek apakah seller exists
            const seller = await this.sellerRepository.findById(sellerId);
            
            if (!seller) {
                throw new NotFound("Seller not found");
            }

            // Update status menjadi 'active'
            const updatedSeller = await this.sellerRepository.updateStatus(
                sellerId, 
                'active', 
                new Date()
            );

            return updatedSeller;

        } catch (error) {
            console.error("ApproveSeller Use Case Error:", error);
            throw error;
        }
    }
}

module.exports = ApproveSeller;