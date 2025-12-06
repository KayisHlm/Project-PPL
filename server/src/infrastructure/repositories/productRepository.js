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
      RETURNING id, seller_id, name, price, weight, stock, category, description, created_at, updated_at
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
      SELECT id, seller_id, name, price, weight, stock, category, description, created_at, updated_at
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
        p.category, p.description, p.created_at, p.updated_at,
        s.shop_name,
        s.pic_province as shop_province,
        s.pic_city as shop_city,
        s.pic_district as shop_district,
        s.pic_village as shop_village,
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
        COALESCE(
          (SELECT json_agg(jsonb_build_object(
            'id', r2.id,
            'rating', r2.rating,
            'comment', r2.comment,
            'name', r2.name,
            'email', r2.email,
            'no_telp', r2.no_telp,
            'province', r2.province,
            'created_at', r2.created_at
          ) ORDER BY r2.created_at DESC)
          FROM reviews r2
          WHERE r2.product_id = p.id),
          '[]'
        ) as reviews,
        COUNT(DISTINCT r.id)::integer as review_count,
        COALESCE(ROUND(AVG(r.rating)::numeric, 1), 0) as average_rating
      FROM products p
      LEFT JOIN sellers s ON p.seller_id = s.id
      LEFT JOIN reviews r ON p.id = r.product_id
      WHERE p.id = $1
      GROUP BY p.id, s.shop_name, s.pic_province, s.pic_city, s.pic_district, s.pic_village
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
      RETURNING id, seller_id, name, price, weight, stock, category, description, created_at, updated_at
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
      SELECT id, seller_id, name, price, weight, stock, category, description, created_at, updated_at
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
        p.category, p.description, p.created_at, p.updated_at,
        s.shop_name,
        s.pic_province as shop_province,
        s.pic_city as shop_city,
        s.pic_district as shop_district,
        s.pic_village as shop_village,
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
        COALESCE(
          (SELECT json_agg(jsonb_build_object(
            'id', r2.id,
            'rating', r2.rating,
            'comment', r2.comment,
            'name', r2.name,
            'email', r2.email,
            'no_telp', r2.no_telp,
            'province', r2.province,
            'created_at', r2.created_at
          ) ORDER BY r2.created_at DESC)
          FROM reviews r2
          WHERE r2.product_id = p.id),
          '[]'
        ) as reviews,
        COUNT(DISTINCT r.id)::integer as review_count,
        COALESCE(ROUND(AVG(r.rating)::numeric, 1), 0) as average_rating
      FROM products p
      LEFT JOIN sellers s ON p.seller_id = s.id
      LEFT JOIN image_products i ON p.id = i.product_id
      LEFT JOIN reviews r ON p.id = r.product_id
      GROUP BY p.id, s.shop_name, s.pic_province, s.pic_city, s.pic_district, s.pic_village
      ORDER BY p.created_at DESC
    `;
    const result = await pool.query(query);
    return result.rows;
  }

  async findByUserId(userId) {
      try {
          console.log(`ProductRepository.findByUserId called for userId: ${userId}`);
          
          const query = `
              SELECT 
                  p.id,
                  p.seller_id,
                  p.name,
                  p.price,
                  p.weight,
                  p.stock,
                  p.category,
                  p.description,
                  p.created_at,
                  p.updated_at,
                  s.shop_name,
                  COALESCE(
                      json_agg(
                          jsonb_build_object(
                              'id', ip.id,
                              'product_id', ip.product_id,
                              'image_url', ip.image_url,
                              'created_at', ip.created_at,
                              'updated_at', ip.updated_at
                          ) ORDER BY ip.created_at ASC
                      ) FILTER (WHERE ip.id IS NOT NULL),
                      '[]'
                  ) AS images,
                  COALESCE(
                      (SELECT json_agg(jsonb_build_object(
                          'id', r2.id,
                          'rating', r2.rating,
                          'comment', r2.comment,
                          'name', r2.name,
                          'email', r2.email,
                          'province', r2.province,
                          'created_at', r2.created_at
                      ) ORDER BY r2.created_at DESC)
                       FROM reviews r2
                       WHERE r2.product_id = p.id),
                      '[]'
                  ) AS reviews,
                  COUNT(DISTINCT r.id)::integer AS review_count,
                  COALESCE(ROUND(AVG(r.rating)::numeric, 1), 0) AS average_rating
              FROM products p
              INNER JOIN sellers s ON p.seller_id = s.id
              LEFT JOIN image_products ip ON p.id = ip.product_id
              LEFT JOIN reviews r ON p.id = r.product_id
              WHERE s.user_id = $1
              GROUP BY 
                  p.id,
                  p.seller_id,
                  p.name,
                  p.price,
                  p.weight,
                  p.stock,
                  p.category,
                  p.description,
                  p.created_at,
                  p.updated_at,
                  s.shop_name
              ORDER BY p.created_at DESC
          `;

          const result = await pool.query(query, [userId]);
          console.log(`Found ${result.rows.length} products for userId: ${userId}`);
          return result.rows;
      } catch (error) {
          console.error('Error in findByUserId:', error);
          throw error;
      }
  }

  async updateStock(productId, newStock) {
    const query = `
      UPDATE products 
      SET stock = $2
      WHERE id = $1
      RETURNING id, seller_id, name, price, weight, stock, category, description, created_at, updated_at
    `;
    const result = await pool.query(query, [productId, parseInt(newStock, 10)]);
    return result.rows[0];
  }
}

module.exports = ProductRepository;
