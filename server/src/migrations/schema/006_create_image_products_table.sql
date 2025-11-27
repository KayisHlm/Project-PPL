CREATE TABLE IF NOT EXISTS image_products (
    id BIGSERIAL PRIMARY KEY,
    product_id BIGINT NOT NULL REFERENCES products(id_products) ON DELETE CASCADE,
    image_url TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_image_products_product_id ON image_products(product_id);

-- Trigger untuk update timestamp
CREATE OR REPLACE FUNCTION update_image_products_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_image_products_updated_at BEFORE UPDATE ON image_products
    FOR EACH ROW EXECUTE FUNCTION update_image_products_updated_at_column();