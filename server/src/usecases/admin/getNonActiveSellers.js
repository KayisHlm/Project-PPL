class GetNonActiveSellers {
    constructor(sellerRepository) {
        this.sellerRepository = sellerRepository;
    }

    async execute() {
        try {
            console.log('GetNonActiveSellers.execute() called');
            
            const sellers = await this.sellerRepository.findNonActiveSellers();
            
            console.log(`Found ${sellers.length} non-active sellers`);

            return {
                sellers,
                total: sellers.length
            };

        } catch (error) {
            console.error("GetNonActiveSellers Use Case Error:", error);
            throw error;
        }
    }
}

module.exports = GetNonActiveSellers;
