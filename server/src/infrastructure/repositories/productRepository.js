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

  async findByIdWithDetails(productId) {
    const query = `
      SELECT 
        p.id, p.seller_id, p.name, p.price, p.weight, p.stock, 
        p.category, p.description, p.rating, p.created_at, p.updated_at,
        
        -- Get seller/shop info
        s.shop_name,
        
        -- Get images as JSON array (using subquery to avoid duplication from reviews join)
        COALESCE(
          (SELECT json_agg(
            jsonb_build_object(
              'id', i.id,
              'product_id', i.product_id,
              'image_url', i.image_url,
              'created_at', i.created_at,
              'updated_at', i.updated_at
            ) ORDER BY i.created_at ASC
          )
          FROM image_products i
          WHERE i.product_id = p.id),
          '[]'
        ) as images,
        
        -- Get review count and average rating
        COUNT(r.id)::integer as review_count,
        COALESCE(ROUND(AVG(r.rating)::numeric, 1), 0) as average_rating
        
      FROM products p
      LEFT JOIN sellers s ON p.seller_id = s.id
      LEFT JOIN reviews r ON p.id = r.product_id
      WHERE p.id = $1
      GROUP BY p.id, s.shop_name
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

  async listAllProduct() {
    const query = `
      SELECT 
        p.id, p.seller_id, p.name, p.price, p.weight, p.stock, 
        p.category, p.description, p.rating, p.created_at, p.updated_at,
        
        -- Get seller/shop info
        s.shop_name,
        
        -- Get images as JSON array
        COALESCE(
          json_agg(
            jsonb_build_object(
              'id', i.id,
              'product_id', i.product_id,
              'image_url', i.image_url,
              'created_at', i.created_at,
              'updated_at', i.updated_at
            ) ORDER BY i.created_at ASC
          ) FILTER (WHERE i.id IS NOT NULL),
          '[]'
        ) as images,
        
        -- Get review count and average rating
        COUNT(DISTINCT r.id)::integer as review_count,
        COALESCE(ROUND(AVG(r.rating)::numeric, 1), 0) as average_rating
        
      FROM products p
      LEFT JOIN sellers s ON p.seller_id = s.id
      LEFT JOIN image_products i ON p.id = i.product_id
      LEFT JOIN reviews r ON p.id = r.product_id
      GROUP BY p.id, s.shop_name
      ORDER BY p.created_at DESC
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
