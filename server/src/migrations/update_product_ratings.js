/**
 * Script untuk update rating produk berdasarkan rata-rata rating di tabel reviews
 * Jalankan: node src/migrations/update_product_ratings.js
 */

const pool = require("../db");

async function updateProductRatings() {
  try {
    console.log("🔄 Starting product ratings update...");

    // Update rating semua produk berdasarkan rata-rata review
    const updateQuery = `
      UPDATE products p
      SET rating = COALESCE(
        (SELECT ROUND(AVG(r.rating)::numeric, 1)
         FROM reviews r
         WHERE r.product_id = p.id),
        0
      )
    `;

    const result = await pool.query(updateQuery);
    console.log(`Successfully updated ${result.rowCount} products`);

    // Tampilkan hasil update
    const checkQuery = `
      SELECT 
        p.id,
        p.name,
        p.rating as product_rating,
        COALESCE(ROUND(AVG(r.rating)::numeric, 1), 0) as calculated_rating,
        COUNT(r.id) as review_count
      FROM products p
      LEFT JOIN reviews r ON p.id = r.product_id
      GROUP BY p.id, p.name, p.rating
      ORDER BY p.id
      LIMIT 10
    `;

    const checkResult = await pool.query(checkQuery);
    console.log("\n📊 Sample of updated products:");
    console.table(checkResult.rows);

    await pool.end();
    console.log("\n✨ Migration completed successfully!");
  } catch (error) {
    console.error("Error updating product ratings:", error.message);
    process.exit(1);
  }
}

updateProductRatings();
