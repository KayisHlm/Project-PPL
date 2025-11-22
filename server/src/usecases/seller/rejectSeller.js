const { NotFound } = require("../../domain/errors");

class RejectSeller {
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

            // Update status menjadi 'rejected'
            const updatedSeller = await this.sellerRepository.updateStatus(
                sellerId, 
                'rejected', 
                null
            );

            return updatedSeller;

        } catch (error) {
            console.error("RejectSeller Use Case Error:", error);
            throw error;
        }
    }
}

module.exports = RejectSeller;