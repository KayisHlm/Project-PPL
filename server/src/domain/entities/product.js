class product {
    constructor({
        id,
        seller_id,
        name,
        price,
        weight,
        stock,
        category,
        description,
        rating,
        created_at,
        updated_at
    }) {
        this.id = id;
        this.sellerId = seller_id;
        this.name = name;
        this.price = parseFloat(price);
        this.weight = weight;
        this.stock = stock;
        this.category = category;
        this.description = description;
        this.rating = parseFloat(rating || 0);
        this.createdAt = created_at;
        this.updatedAt = updated_at;
    }
}

module.exports = product;