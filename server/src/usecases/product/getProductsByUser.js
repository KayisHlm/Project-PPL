class GetProductsByUser {
    constructor(productRepository) {
        this.productRepository = productRepository;
    }

    async execute(userId) {
        try {
            console.log(`GetProductsByUser.execute() called for userId: ${userId}`);
            
            if (!userId) {
                throw new Error('User ID is required');
            }

            const products = await this.productRepository.findByUserId(userId);
            
            console.log(`Found ${products.length} products for user ${userId}`);

            return {
                products,
                total: products.length
            };

        } catch (error) {
            console.error("GetProductsByUser Use Case Error:", error);
            throw error;
        }
    }
}

module.exports = GetProductsByUser;