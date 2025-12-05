class category {
    constructor({ 
        id, 
        name,
        created_at, 
        updated_at
    }) {
        this.id = id;
        this.name = name;
        this.createdAt = created_at;
        this.updatedAt = updated_at;
    }
}

module.exports = category;