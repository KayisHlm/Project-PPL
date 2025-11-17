/**
 * Database Reset Script
 * 
 * ⚠️ WARNING: This script will DROP and recreate the database!
 * All data will be lost. Use with caution!
 * 
 * This is useful for development when you need to reset the database
 * to a clean state before running migrations again.
 * 
 * Usage:
 *   node src/migrations/reset_database.js
 * 
 * After running this script, you need to run migrations again:
 *   node src/migrations/migrate.js
 *   node src/migrations/migrate.js seed
 */

require("dotenv").config();
const { Client } = require("pg");

async function dropAndCreateDatabase() {
  // Connect to default 'postgres' database
  const client = new Client({
    user: process.env.DB_USER,
    host: process.env.DB_HOST,
    database: "postgres",
    password: process.env.DB_PASSWORD,
    port: process.env.DB_PORT,
  });

  try {
    await client.connect();
    console.log("🔌 Connected to PostgreSQL server");

    const dbName = process.env.DB_NAME;

    // Terminate existing connections to the database
    await client.query(`
      SELECT pg_terminate_backend(pg_stat_activity.pid)
      FROM pg_stat_activity
      WHERE pg_stat_activity.datname = '${dbName}'
        AND pid <> pg_backend_pid();
    `);
    console.log(`🔌 Terminated existing connections to '${dbName}'`);

    // Drop database if exists
    await client.query(`DROP DATABASE IF EXISTS ${dbName}`);
    console.log(`🗑️  Database '${dbName}' dropped`);

    // Create database
    await client.query(`CREATE DATABASE ${dbName}`);
    console.log(`✅ Database '${dbName}' created successfully!`);
  } catch (error) {
    console.error("❌ Error:", error.message);
    process.exit(1);
  } finally {
    await client.end();
  }
}

dropAndCreateDatabase();
