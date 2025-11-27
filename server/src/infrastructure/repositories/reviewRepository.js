const pool = require("../../db");

class ReviewRepository {
  async create(productId, data) {
    const {
      email, name, no_telp, rating, comment
    } = data;

    const query = `
      INSERT INTO reviews (
        product_id, email, name, no_telp, rating, comment
      ) VALUES (
        $1, $2, $3, $4, $5, $6
      )
      RETURNING id_review as id, product_id, email, name, no_telp, rating, comment, created_at, updated_at
    `;
    const values = [
      productId,
      email,
      name,
      no_telp || null,
      parseInt(rating, 10),
      comment || null
    ];
    const result = await pool.query(query, values);
    return result.rows[0];
  }

  async findById(reviewId) {
    const query = `
      SELECT id_review as id, product_id, email, name, no_telp, rating, comment, created_at, updated_at
      FROM reviews 
      WHERE id_review = $1
    `;
    const result = await pool.query(query, [reviewId]);
    return result.rows[0];
  }

  async update(reviewId, data) {
    const {
      email, name, no_telp, rating, comment
    } = data;

    const query = `
      UPDATE reviews 
      SET email = $2, name = $3, no_telp = $4, rating = $5, comment = $6
      WHERE id_review = $1
      RETURNING id_review as id, product_id, email, name, no_telp, rating, comment, created_at, updated_at
    `;
    const values = [
      reviewId,
      email,
      name,
      no_telp || null,
      parseInt(rating, 10),
      comment || null
    ];
    const result = await pool.query(query, values);
    return result.rows[0];
  }

  async delete(reviewId) {
    const query = `
      DELETE FROM reviews WHERE id_review = $1 RETURNING id_review as id
    `;
    const result = await pool.query(query, [reviewId]);
    return result.rows[0];
  }

  async listByProduct(productId) {
    const query = `
      SELECT id_review as id, product_id, email, name, no_telp, rating, comment, created_at, updated_at
      FROM reviews 
      WHERE product_id = $1 
      ORDER BY created_at DESC
    `;
    const result = await pool.query(query, [productId]);
    return result.rows;
  }

  async listByEmail(email) {
    const query = `
      SELECT id_review as id, product_id, email, name, no_telp, rating, comment, created_at, updated_at
      FROM reviews 
      WHERE email = $1 
      ORDER BY created_at DESC
    `;
    const result = await pool.query(query, [email]);
    return result.rows;
  }

  async listAll() {
    const query = `
      SELECT id_review as id, product_id, email, name, no_telp, rating, comment, created_at, updated_at
      FROM reviews 
      ORDER BY created_at DESC
    `;
    const result = await pool.query(query);
    return result.rows;
  }

  async getAverageRating(productId) {
    const query = `
      SELECT COALESCE(AVG(rating), 0) AS avg_rating, COUNT(*) AS total_reviews
      FROM reviews 
      WHERE product_id = $1
    `;
    const result = await pool.query(query, [productId]);
    return {
      avgRating: parseFloat(result.rows[0].avg_rating),
      totalReviews: parseInt(result.rows[0].total_reviews, 10)
    };
  }

  async getRatingDistribution(productId) {
    const query = `
      SELECT rating, COUNT(*) AS count
      FROM reviews 
      WHERE product_id = $1
      GROUP BY rating
      ORDER BY rating DESC
    `;
    const result = await pool.query(query, [productId]);
    return result.rows;
  }

  async countByProduct(productId) {
    const query = `
      SELECT COUNT(*) AS count
      FROM reviews 
      WHERE product_id = $1
    `;
    const result = await pool.query(query, [productId]);
    return parseInt(result.rows[0].count, 10);
  }

  async checkUserReviewed(productId, email) {
    const query = `
      SELECT id
      FROM reviews 
      WHERE product_id = $1 AND email = $2
      LIMIT 1
    `;
    const result = await pool.query(query, [productId, email]);
    return result.rows.length > 0;
  }
}

module.exports = ReviewRepository;
