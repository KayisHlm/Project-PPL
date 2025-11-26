-- Seeder: Sample data for users and sellers tables
-- This will add sample users and sellers for testing

-- Insert sample users
INSERT INTO users (email, password, role) VALUES
    ('admin@platform.com', '$2b$10$YourHashedPasswordHere1', 'platform_admin'),
    ('seller1@shop.com', '$2b$10$YourHashedPasswordHere2', 'seller'),
    ('seller2@shop.com', '$2b$10$YourHashedPasswordHere3', 'seller'),
    ('platform.admin@example.com', '$2b$10$PG9WPBpiZAPYAjCobfYotulAzfbLsSee7wAQ/DTxdST2tz.r7CUpC', 'platform_admin'),
    ('seller_api@example.com', '$2b$10$PG9WPBpiZAPYAjCobfYotulAzfbLsSee7wAQ/DTxdST2tz.r7CUpC', 'seller'),
    ('seller_new@example.com', '$2b$10$PG9WPBpiZAPYAjCobfYotulAzfbLsSee7wAQ/DTxdST2tz.r7CUpC', 'seller')
ON CONFLICT (email) DO NOTHING;

-- Insert sample sellers
-- Note: Make sure the user_id matches the actual user IDs created above
INSERT INTO sellers (
    user_id, 
    shop_name, 
    shop_description,
    pic_name,
    pic_phone_number,
    pic_email,
    pic_address,
    pic_rt,
    pic_rw,
    pic_province,
    pic_city,
    pic_district,
    pic_village,
    pic_ktp_number,
    status
) VALUES
    (
        (SELECT id FROM users WHERE email = 'seller1@shop.com'),
        'Toko Elektronik Jaya',
        'Menjual berbagai peralatan elektronik berkualitas',
        'Budi Santoso',
        '081234567890',
        'budi.santoso@email.com',
        'Jl. Merdeka No. 123',
        '001',
        '005',
        'DKI Jakarta',
        'Jakarta Selatan',
        'Kebayoran Baru',
        'Melawai',
        '3271012345670001',
        'approved'
    ),
    (
        (SELECT id FROM users WHERE email = 'seller2@shop.com'),
        'Toko Fashion Trendy',
        'Pusat fashion trendy dan terkini',
        'Siti Rahayu',
        '082345678901',
        'siti.rahayu@email.com',
        'Jl. Sudirman No. 456',
        '002',
        '003',
        'DKI Jakarta',
        'Jakarta Pusat',
        'Menteng',
        'Gondangdia',
        '3271012345670002',
        'pending'
    )
ON CONFLICT (user_id) DO NOTHING;

-- Create seller profile for seller_new@example.com
INSERT INTO sellers (
    user_id,
    shop_name,
    shop_description,
    pic_name,
    pic_phone_number,
    pic_email,
    pic_address,
    pic_rt,
    pic_rw,
    pic_province,
    pic_city,
    pic_district,
    pic_village,
    pic_ktp_number,
    status
) VALUES (
    (SELECT id FROM users WHERE email = 'seller_new@example.com'),
    'Toko Baru',
    'Toko baru untuk pengujian API',
    'Andi',
    '081234567891',
    'andi@example.com',
    'Jl. Katalis No. 10',
    '003',
    '004',
    'Jawa Barat',
    'Bandung',
    'Coblong',
    'Dago',
    '3271012345670003',
    'approved'
) ON CONFLICT (user_id) DO NOTHING;
