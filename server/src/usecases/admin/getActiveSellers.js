class GetActiveSellers {
    constructor(sellerRepository) {
        this.sellerRepository = sellerRepository;
    }

    async execute() {
        try {
            console.log('GetActiveSellers.execute() called');
            
            const sellers = await this.sellerRepository.findActiveSellers();
            
            console.log(`Found ${sellers.length} active sellers`);

            return {
                sellers,
                total: sellers.length
            };

        } catch (error) {
            console.error("GetActiveSellers Use Case Error:", error);
            throw error;
        }
    }
}

module.exports = GetActiveSellers;
