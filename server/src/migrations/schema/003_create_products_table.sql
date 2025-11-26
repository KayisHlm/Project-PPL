CREATE TABLE IF NOT EXISTS products (
  id BIGSERIAL PRIMARY KEY,
  seller_id BIGINT NOT NULL REFERENCES sellers(id) ON DELETE CASCADE,
  name VARCHAR(255) NOT NULL,
  condition VARCHAR(20) NOT NULL CHECK (condition IN ('Baru','Bekas')),
  price INTEGER NOT NULL,
  weight INTEGER NOT NULL,
  min_order INTEGER NOT NULL DEFAULT 1,
  category VARCHAR(100),
  warranty VARCHAR(50),
  year INTEGER,
  claim TEXT,
  description TEXT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_products_seller_id ON products(seller_id);
