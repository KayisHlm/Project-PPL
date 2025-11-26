const pool = require("../../db");

class ProductRepository {
  async create(sellerId, data) {
    const {
      name, condition, price, weight, minOrder,
      category, warranty, year, claim, description
    } = data;

    const query = `
      INSERT INTO products (
        seller_id, name, condition, price, weight, min_order,
        category, warranty, year, claim, description, cover_image,
        created_at, updated_at
      ) VALUES (
        $1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP
      )
      RETURNING *
    `;
    const values = [
      sellerId, name, condition, parseInt(price,10), parseInt(weight,10), parseInt(minOrder,10),
      category || null, warranty || null, year ? parseInt(year,10) : null, claim || null, description || null, data.cover_image || null
    ];
    const result = await pool.query(query, values);
    return result.rows[0];
  }

  async listBySeller(sellerId) {
    const query = `
      SELECT * FROM products WHERE seller_id = $1 ORDER BY created_at DESC
    `;
    const result = await pool.query(query, [sellerId]);
    return result.rows;
  }

  async categoriesBySeller(sellerId) {
    const query = `
      SELECT COALESCE(category,'Lainnya') AS category, COUNT(*) AS count
      FROM products
      WHERE seller_id = $1
      GROUP BY COALESCE(category,'Lainnya')
      ORDER BY category
    `;
    const result = await pool.query(query, [sellerId]);
    return result.rows;
  }
}

module.exports = ProductRepository;
