const pool = require("../../db");

class StatisticsRepository {
  // Get count of products grouped by category
  async getProductsByCategory() {
    const query = `
      SELECT 
        COALESCE(category, 'Uncategorized') as category,
        COUNT(*) as count
      FROM products
      GROUP BY category
      ORDER BY count DESC
    `;
    const result = await pool.query(query);
    return result.rows;
  }

  // Get count of shops grouped by province
  async getShopsByProvince() {
    const query = `
      SELECT 
        pic_province as province,
        COUNT(*) as count
      FROM sellers
      WHERE status = 'approved'
      GROUP BY pic_province
      ORDER BY count DESC
    `;
    const result = await pool.query(query);
    return result.rows;
  }

  // Get count of approved vs pending sellers
  async getSellersActiveStatus() {
    const query = `
      SELECT 
        CASE 
          WHEN status = 'approved' THEN 'approved'
          WHEN status = 'pending' THEN 'pending'
          ELSE 'rejected'
        END as status,
        COUNT(*) as count
      FROM sellers
      GROUP BY status
      ORDER BY count DESC
    `;
    const result = await pool.query(query);
    return result.rows;
  }

  // Get review and rating statistics
  async getReviewsRatingStats() {
    const query = `
      SELECT 
        COUNT(*) as total_reviews,
        ROUND(AVG(rating)::numeric, 2) as average_rating,
        COUNT(CASE WHEN rating = 5 THEN 1 END) as five_star,
        COUNT(CASE WHEN rating = 4 THEN 1 END) as four_star,
        COUNT(CASE WHEN rating = 3 THEN 1 END) as three_star,
        COUNT(CASE WHEN rating = 2 THEN 1 END) as two_star,
        COUNT(CASE WHEN rating = 1 THEN 1 END) as one_star
      FROM reviews
    `;
    const result = await pool.query(query);
    return result.rows[0];
  }
}

module.exports = StatisticsRepository;
