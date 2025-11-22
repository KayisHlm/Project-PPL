const pool = require("../../db");
const Seller = require("../../domain/entities/seller");
const User = require("../../domain/entities/user");

class SellerRepository {
    /**
     * Ambil semua seller dengan status 'pending' (belum terverifikasi)
     */
    async findAllPendingSeller() {
        const client = await pool.connect();
        
        try {
            const query = `SELECT * FROM sellers WHERE status = 'pending' ORDER BY created_at DESC;`;
            const result = await client.query(query);
    
            const rows = result.rows.map(row=> new Seller({
                id: row.id,
                userId: row.user_id,
                shopName: row.shop_name,
                shopDescription: row.shop_description,
                picName: row.pic_name,
                picPhoneNumber: row.pic_phone_number,
                picEmail: row.pic_email,
                picAddress: row.pic_address,
                picRt: row.pic_rt,
                picRw: row.pic_rw,
                picProvince: row.pic_province,
                picCity: row.pic_city,
                picDistrict: row.pic_district,
                picVillage: row.pic_village,
                picKtpNumber: row.pic_ktp_number,
                picPhotoPath: row.pic_photo_path,
                picKtpPath: row.pic_ktp_path,
                status: row.status,
                rejectionReason: row.rejection_reason,
                verifiedAt: row.verified_at,
                createdAt: row.created_at,
                updatedAt: row.updated_at
                }));

            return rows;
        } catch (error) {
            throw error;
        } finally {
            client.release();
        }
    }

    /**
     * Ambil semua seller (untuk statistik admin)
     */
    async findAllSellers() {
        try {
            const query = `
                SELECT 
                    s.id as seller_id,
                    s.user_id,
                    s.shop_name,
                    s.shop_description,
                    s.pic_name,
                    s.pic_phone,
                    s.pic_email,
                    s.pic_ktp,
                    s.address,
                    s.province_id,
                    s.city_id,
                    s.district_id,
                    s.village_id,
                    s.postal_code,
                    s.status,
                    s.verified_at,
                    s.created_at,
                    s.updated_at,
                    u.email,
                    u.role
                FROM sellers s
                INNER JOIN users u ON s.user_id = u.id
                ORDER BY s.created_at DESC
            `;

            const result = await pool.query(query);
            return result.rows.map(row => this.mapRowToSeller(row));

        } catch (error) {
            console.error("FindAllSellers Error:", error);
            throw error;
        }
    }

    /**
     * Ambil seller berdasarkan ID
     */
    async findById(id) {
        try {
            const query = `
                SELECT 
                    s.id as seller_id,
                    s.user_id,
                    s.shop_name,
                    s.shop_description,
                    s.pic_name,
                    s.pic_phone,
                    s.pic_email,
                    s.pic_ktp,
                    s.address,
                    s.province_id,
                    s.city_id,
                    s.district_id,
                    s.village_id,
                    s.postal_code,
                    s.status,
                    s.verified_at,
                    s.created_at,
                    s.updated_at,
                    u.email,
                    u.role
                FROM sellers s
                INNER JOIN users u ON s.user_id = u.id
                WHERE s.id = $1
            `;

            const result = await pool.query(query, [id]);

            if (result.rows.length === 0) {
                return null;
            }

            return this.mapRowToSeller(result.rows[0]);

        } catch (error) {
            console.error("FindById Error:", error);
            throw error;
        }
    }

    /**
     * Update status seller (approve/reject)
     */
    async updateStatus(id, status, verifiedAt = null) {
        try {
            const query = `
                UPDATE sellers
                SET 
                    status = $1,
                    verified_at = $2,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = $3
                RETURNING *
            `;

            const result = await pool.query(query, [status, verifiedAt, id]);

            if (result.rows.length === 0) {
                return null;
            }

            return new Seller(result.rows[0]);

        } catch (error) {
            console.error("UpdateStatus Error:", error);
            throw error;
        }
    }
}

module.exports = SellerRepository;