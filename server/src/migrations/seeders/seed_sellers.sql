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
        'Gunung',
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
        'Kebon Sirih',
        '3271012345670002',
        'approved'
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
    'Sukasari',
    'Gegerkalong',
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
    'Cipaganti',
    '3217198401210004',
    'approved'
);

-- Create seller profile
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
    (SELECT id FROM users WHERE email = 'hasan@example.com'),
    'Toko Laptop Hebat',
    'Toko laptop dengan berbagai merk terkenal',
    'Hasan',
    '081841934131',
    'hasan@example.com',
    'Jl. Cihampelas No. 15',
    '002',
    '006',
    'Jawa Barat',
    'Bandung',
    'Cidadap',
    'Ciumbuleuit',
    '3219384599523005',
    'pending'
);

-- Create seller profile
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
    (SELECT id FROM users WHERE email = 'budi@example.com'),
    'Toko Handphone Mantap',
    'Toko handphone dengan harga bersaing',
    'Budi',
    '081841918313',
    'budi@example.com',
    'Jl. Asia Afrika No. 50',
    '005',
    '002',
    'Jawa Barat',
    'Bandung',
    'Sumur Bandung',
    'Braga',
    '3219384599523006',
    'pending'
);

INSERT INTO sellers ("user_id", "shop_name", "shop_description", "pic_name", "pic_phone_number", "pic_email", "pic_address", "pic_rt", "pic_rw", "pic_province", "pic_city", "pic_district", "pic_village", "pic_ktp_number", "pic_photo_path", "pic_ktp_path", "status", "rejection_reason", "verified_at") VALUES
(9, 'Toko Kevin', 'Kelontong', 'Kevin Adi Santoso', '089515303060', 'kevinadsantoso@gmail.com', 'Jl.Parasamya tengah No.149', '001', '005', 'Jawa Tengah', 'Kabupaten Semarang', 'Ungaran Timur', 'Gedanganak', '1234567890987654', 'temp/d5bfe436-9f31-42b8-8f00-ffe42610b3a4_1764850768.jpg', 'temp/422e3131-7f71-42a0-92de-ca61f89bd41d_1764850743.png', 'approved', NULL, '2025-12-04 19:48:08.556');