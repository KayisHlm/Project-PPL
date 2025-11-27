class GetApprovedSellers {
    constructor(sellerRepository) {
        this.sellerRepository = sellerRepository;
    }

    async execute() {
        try {
            console.log('GetApprovedSellers.execute() called');
            
            const sellers = await this.sellerRepository.findApprovedSellers();
            
            console.log(`Found ${sellers.length} approved sellers`);

            return {
                sellers,
                total: sellers.length
            };

        } catch (error) {
            console.error("GetApprovedSellers Use Case Error:", error);
            throw error;
        }
    }
}

module.exports = GetApprovedSellers;
