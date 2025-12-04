-- Seeder: Sample data for users and sellers tables
-- This will add sample users and sellers for testing

-- Insert sample users
INSERT INTO users (email, password, role) VALUES
    ('admin@platform.com', '$2b$10$YourHashedPasswordHere1', 'platform_admin'),
    ('platform.admin@example.com', '$2b$10$PG9WPBpiZAPYAjCobfYotulAzfbLsSee7wAQ/DTxdST2tz.r7CUpC', 'platform_admin'),
    ('seller1@shop.com', '$2b$10$YourHashedPasswordHere2', 'seller'),
    ('seller2@shop.com', '$2b$10$YourHashedPasswordHere3', 'seller'),
    ('seller_api@example.com', '$2b$10$PG9WPBpiZAPYAjCobfYotulAzfbLsSee7wAQ/DTxdST2tz.r7CUpC', 'seller'),
    ('seller_new@example.com', '$2b$10$PG9WPBpiZAPYAjCobfYotulAzfbLsSee7wAQ/DTxdST2tz.r7CUpC', 'seller'),
    ('hasan@example.com', '$2b$10$PG9WPBpiZAPYAjCobfYotulAzfbLsSee7wAQ/DTxdST2tz.r7CUpC', 'seller'),
    ('budi@example.com', '$2b$10$PG9WPBpiZAPYAjCobfYotulAzfbLsSee7wAQ/DTxdST2tz.r7CUpC', 'seller'),
    ('kevinadsantoso@gmail.com', '$2b$10$VNRP86qb.L6/64bgs6izdepND96r1MoFV/t8sVA/S9Tv9nhCS74iq', 'seller')
ON CONFLICT (email) DO NOTHING;