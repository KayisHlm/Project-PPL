const pool = require("../../db");

class CategoryRepository {
  async createGlobal(name) {
    const check = await pool.query(
      `SELECT id FROM categories WHERE seller_id IS NULL AND name = $1 LIMIT 1`,
      [name]
    );
    if (check.rows.length > 0) {
      return null;
    }
    const insert = await pool.query(
      `INSERT INTO categories (seller_id, name, created_at, updated_at)
       VALUES (NULL, $1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
       RETURNING *`,
      [name]
    );
    return insert.rows[0] || null;
  }

  async listAll() {
    const result = await pool.query(
      `SELECT id, name FROM categories WHERE seller_id IS NULL ORDER BY name ASC`
    );
    return result.rows;
  }

  async listWithCountAll() {
    const result = await pool.query(
      `SELECT c.id, c.name, COALESCE(p.count, 0) AS count
       FROM categories c
       LEFT JOIN (
         SELECT category, COUNT(*) AS count
         FROM products
         GROUP BY category
       ) p ON p.category = c.name
       WHERE c.seller_id IS NULL
       ORDER BY c.name ASC`
    );
    return result.rows;
  }
}

module.exports = CategoryRepository;
