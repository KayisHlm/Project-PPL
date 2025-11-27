const pool = require("../../db");

class ImageRepository {
  async create(productId, imageUrl) {
    const query = `
      INSERT INTO image_products (
        product_id, image_url
      ) VALUES (
        $1, $2
      )
      RETURNING id, product_id, image_url, created_at, updated_at
    `;
    const values = [productId, imageUrl];
    const result = await pool.query(query, values);
    return result.rows[0];
  }

  async createBulk(productId, imageUrls) {
    const values = [];
    const placeholders = [];
    
    imageUrls.forEach((url, index) => {
      const baseIndex = index * 2;
      placeholders.push(`($${baseIndex + 1}, $${baseIndex + 2})`);
      values.push(productId, url);
    });

    const query = `
      INSERT INTO image_products (product_id, image_url)
      VALUES ${placeholders.join(', ')}
      RETURNING id, product_id, image_url, created_at, updated_at
    `;
    const result = await pool.query(query, values);
    return result.rows;
  }

  async findById(imageId) {
    const query = `
      SELECT id, product_id, image_url, created_at, updated_at
      FROM image_products 
      WHERE id = $1
    `;
    const result = await pool.query(query, [imageId]);
    return result.rows[0];
  }

  async update(imageId, imageUrl) {
    const query = `
      UPDATE image_products 
      SET image_url = $2
      WHERE id = $1
      RETURNING id, product_id, image_url, created_at, updated_at
    `;
    const result = await pool.query(query, [imageId, imageUrl]);
    return result.rows[0];
  }

  async delete(imageId) {
    const query = `
      DELETE FROM image_products WHERE id = $1 RETURNING id
    `;
    const result = await pool.query(query, [imageId]);
    return result.rows[0];
  }

  async deleteByProduct(productId) {
    const query = `
      DELETE FROM image_products WHERE product_id = $1 RETURNING id
    `;
    const result = await pool.query(query, [productId]);
    return result.rows;
  }

  async listByProduct(productId) {
    const query = `
      SELECT id, product_id, image_url, created_at, updated_at
      FROM image_products 
      WHERE product_id = $1 
      ORDER BY created_at ASC
    `;
    const result = await pool.query(query, [productId]);
    return result.rows;
  }

  async listAll() {
    const query = `
      SELECT id, product_id, image_url, created_at, updated_at
      FROM image_products 
      ORDER BY created_at DESC
    `;
    const result = await pool.query(query);
    return result.rows;
  }

  async countByProduct(productId) {
    const query = `
      SELECT COUNT(*) AS count
      FROM image_products 
      WHERE product_id = $1
    `;
    const result = await pool.query(query, [productId]);
    return parseInt(result.rows[0].count, 10);
  }

  async getFirstImageByProduct(productId) {
    const query = `
      SELECT id, product_id, image_url, created_at, updated_at
      FROM image_products 
      WHERE product_id = $1 
      ORDER BY created_at ASC
      LIMIT 1
    `;
    const result = await pool.query(query, [productId]);
    return result.rows[0];
  }
}

module.exports = ImageRepository;
