class SellerRepositoryInterface {
    async create(sellerData) {
        throw new Error("Method create() must be implemented.");
    }

    async findById(id) {
        throw new Error("Method findById() must be implemented.");
    }
    
    async findByUserId(userId) {
        throw new Error("Method findByUserId() must be implemented.");
    }

    async findPendingSellers() {
        throw new Error("Method findPendingSellers() must be implemented.");
    }

    async findAllSellers() {
        throw new Error("Method findAllSellers() must be implemented.");
    }

    async updateStatus(id, status, VeriviedAt) {
        throw new Error("Method updateStatus() must be implemented.");
    }

    async update(id, updateData) {
        throw new Error("Method update() must be implemented.");
    }

    async delete(id) {
        throw new Error("Method delete() must be implemented.");
    }

    async isExist(id) {
        throw new Error("Method isExist() must be implemented.");
    }
}