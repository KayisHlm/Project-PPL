class category {
    constructor({ 
        id, 
        name,
        description,
        created_at, 
        updated_at
    }) {
        this.id = id;
        this.name = name;
        this.description = description;
        this.createdAt = created_at;
        this.updatedAt = updated_at;
    }
}

module.exports = category;