class ReviewInformation {
    constructor(review) {
        this.id = review.id;
        this.productId = review.productId || review.product_id;
        this.email = review.email;
        this.name = review.name;
        this.noTelp = review.noTelp || review.no_telp;
        this.rating = parseInt(review.rating, 10);
        this.comment = review.comment;
        this.createdAt = review.createdAt || review.created_at;
        this.updatedAt = review.updatedAt || review.updated_at;
    }
}

module.exports = ReviewInformation;