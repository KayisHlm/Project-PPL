/**
 * Database Migration Runner
 * 
 * This script runs database migrations and seeders for the application.
 * 
 * Usage:
 *   node src/migrations/migrate.js          - Run all migrations
 *   node src/migrations/migrate.js migrate  - Run all migrations
 *   node src/migrations/migrate.js seed     - Run seeders only
 * 
 * Folder Structure:
 *   - schema/   : Contains DDL migrations (CREATE TABLE, ALTER TABLE, etc.)
 *   - seeders/  : Contains DML seeders (INSERT data for testing)
 */

const fs = require("fs");
const path = require("path");
const pool = require("../db");
require("dotenv").config();

async function runMigration() {
  try {
    console.log("🚀 Starting database migration...");

    // Define migration files in order
    const migrationFiles = [
      "001_create_users_table.sql",
      "002_create_sellers_table.sql",
    ];

    // Run each migration file
    for (const fileName of migrationFiles) {
      console.log(`📝 Running migration: ${fileName}`);
      const migrationPath = path.join(__dirname, "schema", fileName);
      const migrationSQL = fs.readFileSync(migrationPath, "utf8");

      // Execute the migration
      await pool.query(migrationSQL);
      console.log(`✅ ${fileName} completed successfully`);
    }

    console.log("✅ All migrations completed successfully!");
    console.log("📊 Verifying migrations...");

    // Verify tables were created
    const tablesCheck = await pool.query(`
            SELECT table_name 
            FROM information_schema.tables 
            WHERE table_schema = 'public' 
            AND table_name IN ('users', 'sellers')
            ORDER BY table_name
        `);

    console.log("📋 Tables created:");
    tablesCheck.rows.forEach((row) => {
      console.log(`   ✅ ${row.table_name}`);
    });

    // Check if users table has data
    const userCount = await pool.query("SELECT COUNT(*) as total FROM users");
    if (userCount.rows[0].total > 0) {
      console.log(`📈 Total users in database: ${userCount.rows[0].total}`);

      // Show users by role
      const roleCount = await pool.query(`
                SELECT role, COUNT(*) as count 
                FROM users 
                GROUP BY role 
                ORDER BY role
            `);

      console.log("👥 Users by role:");
      roleCount.rows.forEach((row) => {
        console.log(`   - ${row.role}: ${row.count} users`);
      });
    } else {
      console.log(
        "ℹ️  No users found. You can add sample users later if needed."
      );
    }

    // Check sellers table
    const sellerCount = await pool.query("SELECT COUNT(*) as total FROM sellers");
    console.log(`🏪 Total sellers in database: ${sellerCount.rows[0].total}`);
  } catch (error) {
    console.error("❌ Migration failed:", error.message);
    console.error(
      '💡 Make sure PostgreSQL is running and database "grade" exists'
    );
    process.exit(1);
  } finally {
    await pool.end();
  }
}

async function runSeederOnly() {
  try {
    console.log("🌱 Starting database seeder...");

    // Read the seeder file
    const seederPath = path.join(__dirname, "seeders", "seed_users_sellers.sql");
    const seederSQL = fs.readFileSync(seederPath, "utf8");

    // Execute the seeder
    await pool.query(seederSQL);

    console.log("✅ Seeder completed successfully!");

    // Show updated counts
    const userCount = await pool.query("SELECT COUNT(*) as total FROM users");
    console.log(`📈 Total users in database: ${userCount.rows[0].total}`);

    const roleCount = await pool.query(`
            SELECT role, COUNT(*) as count 
            FROM users 
            GROUP BY role 
            ORDER BY role
        `);

    console.log("👥 Users by role:");
    roleCount.rows.forEach((row) => {
      console.log(`   - ${row.role}: ${row.count} users`);
    });

    // Show sellers count
    const sellerCount = await pool.query("SELECT COUNT(*) as total FROM sellers");
    console.log(`🏪 Total sellers in database: ${sellerCount.rows[0].total}`);

    const sellerStatus = await pool.query(`
            SELECT status, COUNT(*) as count 
            FROM sellers 
            GROUP BY status 
            ORDER BY status
        `);

    console.log("📊 Sellers by status:");
    sellerStatus.rows.forEach((row) => {
      console.log(`   - ${row.status}: ${row.count} sellers`);
    });
  } catch (error) {
    console.error("❌ Seeder failed:", error.message);
    process.exit(1);
  } finally {
    await pool.end();
  }
}

// Parse command line arguments
const command = process.argv[2];

if (command === "seed") {
  runSeederOnly();
} else if (command === "migrate" || !command) {
  runMigration();
} else {
  console.log("Usage:");
  console.log("  node migrations/migrate.js          # Run full migration");
  console.log("  node migrations/migrate.js migrate  # Run full migration");
  console.log("  node migrations/migrate.js seed     # Run seeder only");
  process.exit(1);
}
