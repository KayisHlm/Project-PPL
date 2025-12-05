const pool = require("../../db");

class CategoryRepository {
  async create(name) {
    const check = await pool.query(
      `SELECT id FROM categories WHERE name = $1 LIMIT 1`,
      [name]
    );
    if (check.rows.length > 0) {
      return null;
    }
    const insert = await pool.query(
      `INSERT INTO categories (name)
       VALUES ($1)
       RETURNING id, name, created_at, updated_at`,
      [name]
    );
    return insert.rows[0] || null;
  }

  async findById(categoryId) {
    const result = await pool.query(
      `SELECT id, name, created_at, updated_at
       FROM categories 
       WHERE id = $1`,
      [categoryId]
    );
    return result.rows[0];
  }

  async findByName(name) {
    const result = await pool.query(
      `SELECT id, name, created_at, updated_at
       FROM categories 
       WHERE name = $1`,
      [name]
    );
    return result.rows[0];
  }

  async update(categoryId, name) {
    const result = await pool.query(
      `UPDATE categories 
       SET name = $2
       WHERE id = $1
       RETURNING id, name, created_at, updated_at`,
      [categoryId, name]
    );
    return result.rows[0];
  }

  async delete(categoryId) {
    const result = await pool.query(
      `DELETE FROM categories 
       WHERE id = $1 
       RETURNING id`,
      [categoryId]
    );
    return result.rows[0];
  }

  async listAll() {
    const result = await pool.query(
      `SELECT id, name, created_at, updated_at
       FROM categories 
       ORDER BY name ASC`
    );
    return result.rows;
  }

  async listWithCount() {
    const result = await pool.query(
      `SELECT c.id, c.name, COALESCE(p.count, 0) AS product_count
       FROM categories c
       LEFT JOIN (
         SELECT category, COUNT(*) AS count
         FROM products
         GROUP BY category
       ) p ON p.category = c.name
       ORDER BY c.name ASC`
    );
    return result.rows;
  }

  async countProducts(categoryName) {
    const result = await pool.query(
      `SELECT COUNT(*) AS count
       FROM products
       WHERE category = $1`,
      [categoryName]
    );
    return parseInt(result.rows[0].count, 10);
  }
}

module.exports = CategoryRepository;
