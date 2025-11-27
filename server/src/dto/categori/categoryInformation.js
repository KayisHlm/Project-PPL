class categoryInformation {
    constructor (category) {
        this.id = category.id;
        this.name = category.name;
        this.description = category.description;
        this.createdAt = category.createdAt || category.created_at;
        this.updatedAt = category.updatedAt || category.updated_at;
    }
}

module.exports = categoryInformation;