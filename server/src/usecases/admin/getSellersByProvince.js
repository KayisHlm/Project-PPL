class GetSellersByProvince {
    constructor(sellerRepository) {
        this.sellerRepository = sellerRepository;
    }

    async execute() {
        try {
            console.log('GetSellersByProvince.execute() called');
            
            const sellers = await this.sellerRepository.findSellersByProvince();
            
            console.log(`Found ${sellers.length} sellers by province`);

            return {
                sellers,
                total: sellers.length
            };

        } catch (error) {
            console.error("GetSellersByProvince Use Case Error:", error);
            throw error;
        }
    }
}

module.exports = GetSellersByProvince;
