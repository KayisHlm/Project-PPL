-- Migration: Create sellers table
-- Menyimpan informasi toko dan data PIC (Person In Charge)

CREATE TABLE IF NOT EXISTS sellers (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL UNIQUE,
    shop_name VARCHAR(255) NOT NULL,
    shop_description TEXT,
    pic_name VARCHAR(255) NOT NULL,
    pic_phone_number VARCHAR(20) NOT NULL,
    pic_email VARCHAR(255) NOT NULL UNIQUE,
    pic_address TEXT NOT NULL,
    pic_rt VARCHAR(10) NOT NULL,
    pic_rw VARCHAR(10) NOT NULL,
    pic_province VARCHAR(255) NOT NULL,
    pic_city VARCHAR(255) NOT NULL,
    pic_district VARCHAR(255) NOT NULL,
    pic_village VARCHAR(255) NOT NULL,
    pic_ktp_number VARCHAR(20) NOT NULL UNIQUE,
    pic_photo_path VARCHAR(500),
    pic_ktp_path VARCHAR(500),
    status VARCHAR(50) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'approved', 'rejected')),
    rejection_reason TEXT,
    verified_at TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Index untuk performa
CREATE INDEX IF NOT EXISTS idx_sellers_user_id ON sellers(user_id);
CREATE INDEX IF NOT EXISTS idx_sellers_status ON sellers(status);
CREATE INDEX IF NOT EXISTS idx_sellers_shop_name ON sellers(shop_name);

-- Trigger untuk update timestamp
CREATE TRIGGER update_sellers_updated_at BEFORE UPDATE ON sellers
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
