/**
 * Database Creation Script
 * 
 * This script creates the PostgreSQL database if it doesn't exist.
 * It connects to the default 'postgres' database to create the new database.
 * 
 * Usage:
 *   node src/migrations/create_database.js
 * 
 * Configuration:
 *   Database name is read from DB_NAME in .env file
 */

require("dotenv").config();
const { Client } = require("pg");

async function createDatabase() {
  // Connect to default 'postgres' database to create new database
  const client = new Client({
    user: process.env.DB_USER,
    host: process.env.DB_HOST,
    database: "postgres", // Connect to default postgres database
    password: process.env.DB_PASSWORD,
    port: process.env.DB_PORT,
  });

  try {
    await client.connect();
    console.log("🔌 Connected to PostgreSQL server");

    const dbName = process.env.DB_NAME;

    // Check if database already exists
    const checkDb = await client.query(
      `SELECT 1 FROM pg_database WHERE datname = $1`,
      [dbName]
    );

    if (checkDb.rows.length > 0) {
      console.log(`ℹ️  Database '${dbName}' already exists`);
    } else {
      // Create database
      await client.query(`CREATE DATABASE ${dbName}`);
      console.log(`✅ Database '${dbName}' created successfully!`);
    }
  } catch (error) {
    console.error("❌ Error creating database:", error.message);
    console.error("\n💡 Tips:");
    console.error("   - Make sure PostgreSQL is running");
    console.error("   - Check DB_USER and DB_PASSWORD in .env file");
    console.error("   - Ensure the user has CREATE DATABASE privilege");
    process.exit(1);
  } finally {
    await client.end();
  }
}

createDatabase();
