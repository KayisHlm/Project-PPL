class review {
    constructor({ 
        id,
        product_id,
        email, 
        name, 
        no_telp, 
        rating, 
        comment,
        created_at, 
        updated_at 
    }) {
        this.id = id;
        this.productId = product_id;
        this.email = email;
        this.name = name;
        this.noTelp = no_telp;
        this.rating = parseInt(rating, 10);
        this.comment = comment;
        this.createdAt = created_at;
        this.updatedAt = updated_at;
    }
}

module.exports = review;