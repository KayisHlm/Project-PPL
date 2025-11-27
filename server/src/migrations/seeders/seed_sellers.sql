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

-- Insert another sample seller
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
    'Toko Furniture Minimalis',
    'Toko furniture dengan desain minimalis modern',
    'Putra',
    '081234567891',
    'putra@example.com',
    'Jl. Mulawarman No. 20',
    '003',
    '004',
    'Jawa Barat',
    'Bandung',
    'Jatinangor',
    'Dago',
    '3271012345670003',
    'approved'
);

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
    (SELECT id FROM users WHERE email = 'seller_api@example.com'),
    'Toko Gaming Pro',
    'Toko khusus perlengkapan gaming',
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
    '3217198401210004',
    'approved'
);