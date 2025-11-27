const pool = require("../../db");

class ProductRepository {
  async create(sellerId, data) {
    const {
      name, price, weight, stock, category, description
    } = data;

    const query = `
      INSERT INTO products (
        seller_id, name, price, weight, stock, category, description
      ) VALUES (
        $1, $2, $3, $4, $5, $6, $7
      )
      RETURNING id, seller_id, name, price, weight, stock, category, description, rating, created_at, updated_at
    `;
    const values = [
      sellerId,
      name,
      parseFloat(price),
      parseInt(weight, 10),
      parseInt(stock, 10),
      category || null,
      description || null
    ];
    const result = await pool.query(query, values);
    return result.rows[0];
  }

  async findById(productId) {
    const query = `
      SELECT id, seller_id, name, price, weight, stock, category, description, rating, created_at, updated_at
      FROM products 
      WHERE id = $1
    `;
    const result = await pool.query(query, [productId]);
    return result.rows[0];
  }

  async update(productId, data) {
    const {
      name, price, weight, stock, category, description
    } = data;

    const query = `
      UPDATE products 
      SET name = $2, price = $3, weight = $4, stock = $5, category = $6, description = $7
      WHERE id = $1
      RETURNING id, seller_id, name, price, weight, stock, category, description, rating, created_at, updated_at
    `;
    const values = [
      productId,
      name,
      parseFloat(price),
      parseInt(weight, 10),
      parseInt(stock, 10),
      category || null,
      description || null
    ];
    const result = await pool.query(query, values);
    return result.rows[0];
  }

  async delete(productId) {
    const query = `
      DELETE FROM products WHERE id = $1 RETURNING id
    `;
    const result = await pool.query(query, [productId]);
    return result.rows[0];
  }

  async listBySeller(sellerId) {
    const query = `
      SELECT id, seller_id, name, price, weight, stock, category, description, rating, created_at, updated_at
      FROM products 
      WHERE seller_id = $1 
      ORDER BY created_at DESC
    `;
    const result = await pool.query(query, [sellerId]);
    return result.rows;
  }

  async categoriesBySeller(sellerId) {
    const query = `
      SELECT COALESCE(category, 'Lainnya') AS category, COUNT(*) AS count
      FROM products
      WHERE seller_id = $1
      GROUP BY COALESCE(category, 'Lainnya')
      ORDER BY category
    `;
    const result = await pool.query(query, [sellerId]);
    return result.rows;
  }

  async listAll() {
    const query = `
      SELECT id, seller_id, name, price, weight, stock, category, description, rating, created_at, updated_at
      FROM products 
      ORDER BY created_at DESC
    `;
    const result = await pool.query(query);
    return result.rows;
  }

  async updateRating(productId, newRating) {
    const query = `
      UPDATE products 
      SET rating = $2
      WHERE id = $1
      RETURNING id, seller_id, name, price, weight, stock, category, description, rating, created_at, updated_at
    `;
    const result = await pool.query(query, [productId, parseFloat(newRating)]);
    return result.rows[0];
  }

  async updateStock(productId, newStock) {
    const query = `
      UPDATE products 
      SET stock = $2
      WHERE id = $1
      RETURNING id, seller_id, name, price, weight, stock, category, description, rating, created_at, updated_at
    `;
    const result = await pool.query(query, [productId, parseInt(newStock, 10)]);
    return result.rows[0];
  }
}

module.exports = ProductRepository;
