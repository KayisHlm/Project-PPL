const pool = require("../../db");

class ImageRepository {
  async create(productId, imageUrl, isCover = false) {
    const query = `
      INSERT INTO image_products (
        product_id, image_url, is_cover
      ) VALUES (
        $1, $2, $3
      )
      RETURNING id, product_id, image_url, is_cover, created_at, updated_at
    `;
    const values = [productId, imageUrl, isCover];
    const result = await pool.query(query, values);
    return result.rows[0];
  }

  async createBulk(productId, imageUrls) {
    const values = [];
    const placeholders = [];
    
    imageUrls.forEach((url, index) => {
      const baseIndex = index * 3;
      const isCover = index === 0; 
      placeholders.push(`($${baseIndex + 1}, $${baseIndex + 2}, $${baseIndex + 3})`);
      values.push(productId, url, isCover);
    });

    const query = `
      INSERT INTO image_products (product_id, image_url, is_cover)
      VALUES ${placeholders.join(', ')}
      RETURNING id, product_id, image_url, is_cover, created_at, updated_at
    `;
    const result = await pool.query(query, values);
    return result.rows;
  }

  async findById(imageId) {
    const query = `
      SELECT id, product_id, image_url, is_cover, created_at, updated_at
      FROM image_products 
      WHERE id = $1
    `;
    const result = await pool.query(query, [imageId]);
    return result.rows[0];
  }

  async update(imageId, imageUrl, isCover) {
    const updates = [];
    const values = [];
    let paramCount = 1;

    if (imageUrl !== undefined) {
      updates.push(`image_url = $${paramCount++}`);
      values.push(imageUrl);
    }

    if (isCover !== undefined) {
      updates.push(`is_cover = $${paramCount++}`);
      values.push(isCover);
    }

    values.push(imageId);

    const query = `
      UPDATE image_products 
      SET ${updates.join(', ')}
      WHERE id = $${paramCount}
      RETURNING id, product_id, image_url, is_cover, created_at, updated_at
    `;
    const result = await pool.query(query, values);
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
      SELECT id, product_id, image_url, is_cover, created_at, updated_at
      FROM image_products 
      WHERE product_id = $1 
      ORDER BY is_cover DESC, created_at ASC
    `;
    const result = await pool.query(query, [productId]);
    return result.rows;
  }

  async listAll() {
    const query = `
      SELECT id, product_id, image_url, is_cover, created_at, updated_at
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
      SELECT id, product_id, image_url, is_cover, created_at, updated_at
      FROM image_products 
      WHERE product_id = $1 
      ORDER BY is_cover DESC, created_at ASC
      LIMIT 1
    `;
    const result = await pool.query(query, [productId]);
    return result.rows[0];
  }

  async getCoverImageByProduct(productId) {
    const query = `
      SELECT id, product_id, image_url, is_cover, created_at, updated_at
      FROM image_products 
      WHERE product_id = $1 AND is_cover = TRUE
      LIMIT 1
    `;
    const result = await pool.query(query, [productId]);
    return result.rows[0];
  }
}

module.exports = ImageRepository;
