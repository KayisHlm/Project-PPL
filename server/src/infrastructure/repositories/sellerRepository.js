const pool = require("../../db");

class SellerRepository {
    /**
     * Ambil semua seller dengan status 'pending'
     */
    async findPendingSellers() {
        const client = await pool.connect();
        
        try {
            console.log("SellerRepository.findPendingSellers() called");
            
            const query = `
                SELECT 
                    s.id,
                    s.user_id,
                    s.shop_name,
                    s.shop_description,
                    s.pic_name,
                    s.pic_phone_number,
                    s.pic_email,
                    s.pic_ktp_number,
                    s.pic_address,
                    s.pic_rt,
                    s.pic_rw,
                    s.pic_province,
                    s.pic_city,
                    s.pic_district,
                    s.pic_village,
                    s.pic_photo_path,
                    s.pic_ktp_path,
                    s.status,
                    s.verified_at,
                    s.created_at,
                    s.updated_at,
                    u.email as user_email
                FROM sellers s
                INNER JOIN users u ON s.user_id = u.id
                WHERE s.status = 'pending'
                ORDER BY s.created_at DESC
            `;

            const result = await client.query(query);
            console.log(`Found ${result.rows.length} pending sellers`);
            
            return result.rows;

        } catch (error) {
            console.error("SellerRepository - findPendingSellers Error:", error);
            throw error;
        } finally {
            client.release();
        }
    }

    async findSellersByProvince() {
        const client = await pool.connect();
        try {
            const query = `
                SELECT 
                    COALESCE(s.pic_province, 'Tidak diketahui') AS province,
                    COALESCE(
                        json_agg(
                            jsonb_build_object(
                                'store_name', s.shop_name,
                                'seller_name', s.pic_name
                            ) ORDER BY s.shop_name ASC
                        ) FILTER (WHERE s.shop_name IS NOT NULL),
                        '[]'
                    ) AS sellers
                FROM sellers s
                WHERE s.status = 'approved'
                GROUP BY COALESCE(s.pic_province, 'Tidak diketahui')
                ORDER BY province ASC
            `;
            const result = await client.query(query);
            return result.rows;
        } catch (error) {
            console.error("SellerRepository - findSellersByProvince Error:", error);
            throw error;
        } finally {
            client.release();
        }
    }
    /**
     * Ambil semua seller dengan status 'approved' ✨ NEW METHOD
     */
    async findApprovedSellers() {
        const client = await pool.connect();
        
        try {
            console.log("SellerRepository.findApprovedSellers() called");
            
            const query = `
                SELECT 
                    s.id,
                    s.user_id,
                    s.shop_name,
                    s.shop_description,
                    s.pic_name,
                    s.pic_phone_number,
                    s.pic_email,
                    s.pic_ktp_number,
                    s.pic_address,
                    s.pic_rt,
                    s.pic_rw,
                    s.pic_province,
                    s.pic_city,
                    s.pic_district,
                    s.pic_village,
                    s.pic_photo_path,
                    s.pic_ktp_path,
                    s.status,
                    s.verified_at,
                    s.created_at,
                    s.updated_at,
                    u.email as user_email
                FROM sellers s
                INNER JOIN users u ON s.user_id = u.id
                WHERE s.status = 'approved'
                ORDER BY s.verified_at DESC, s.created_at DESC
            `;

            const result = await client.query(query);
            console.log(`Found ${result.rows.length} approved sellers`);
            
            return result.rows;

        } catch (error) {
            console.error("SellerRepository - findApprovedSellers Error:", error);
            throw error;
        } finally {
            client.release();
        }
    }
    
    /**
     * Ambil semua seller dengan status 'NonActive' 
     */

    async findNonActiveSellers() {
        const client = await pool.connect();
        
        try {
            console.log("SellerRepository.findNonActiveSellers() called");
            
            const query = `
                SELECT 
                    s.id,
                    s.user_id,
                    s.shop_name,
                    s.shop_description,
                    s.pic_name,
                    s.pic_phone_number,
                    s.pic_email,
                    s.pic_ktp_number,
                    s.pic_address,
                    s.pic_rt,
                    s.pic_rw,
                    s.pic_province,
                    s.pic_city,
                    s.pic_district,
                    s.pic_village,
                    s.pic_photo_path,
                    s.pic_ktp_path,
                    s.status,
                    s.verified_at,
                    s.created_at,
                    s.updated_at,
                    u.email as user_email
                FROM sellers s
                INNER JOIN users u ON s.user_id = u.id
                WHERE s.status = 'pending' OR s.status = 'rejected'
                ORDER BY s.created_at DESC
            `;

            const result = await client.query(query);
            console.log(`Found ${result.rows.length} non-active sellers`);
            
            return result.rows;

        } catch (error) {
            console.error("SellerRepository - findNonActiveSellers Error:", error);
            throw error;
        } finally {
            client.release();
        }
    }
    
    /**
     * Ambil semua seller dengan status 'Active' 
     */
    async findActiveSellers() {
        const client = await pool.connect();
        
        try {
            console.log("SellerRepository.findActiveSellers() called");
            
            const query = `
                SELECT 
                    s.id,
                    s.user_id,
                    s.shop_name,
                    s.shop_description,
                    s.pic_name,
                    s.pic_phone_number,
                    s.pic_email,
                    s.pic_ktp_number,
                    s.pic_address,
                    s.pic_rt,
                    s.pic_rw,
                    s.pic_province,
                    s.pic_city,
                    s.pic_district,
                    s.pic_village,
                    s.pic_photo_path,
                    s.pic_ktp_path,
                    s.status,
                    s.verified_at,
                    s.created_at,
                    s.updated_at,
                    u.email as user_email
                FROM sellers s
                INNER JOIN users u ON s.user_id = u.id
                WHERE s.status = 'approved'
                ORDER BY s.verified_at DESC, s.created_at DESC
            `;

            const result = await client.query(query);
            console.log(`Found ${result.rows.length} active sellers`);
            
            return result.rows;

        } catch (error) {
            console.error("SellerRepository - findActiveSellers Error:", error);
            throw error;
        } finally {
            client.release();
        }
    }
    

    /**
     * Ambil seller by ID
     */
    async findById(sellerId) {
        const client = await pool.connect();
        
        try {
            console.log(`SellerRepository.findById(${sellerId}) called`);
            
            const query = `
                SELECT 
                    s.id,
                    s.user_id,
                    s.shop_name,
                    s.shop_description,
                    s.pic_name,
                    s.pic_phone_number,
                    s.pic_email,
                    s.pic_ktp_number,
                    s.pic_address,
                    s.pic_rt,
                    s.pic_rw,
                    s.pic_province,
                    s.pic_city,
                    s.pic_district,
                    s.pic_village,
                    s.pic_photo_path,
                    s.pic_ktp_path,
                    s.status,
                    s.verified_at,
                    s.created_at,
                    s.updated_at,
                    u.email as user_email,
                    u.role
                FROM sellers s
                INNER JOIN users u ON s.user_id = u.id
                WHERE s.id = $1
            `;

            const result = await client.query(query, [sellerId]);
            return result.rows[0] || null;

        } catch (error) {
            console.error("SellerRepository - findById Error:", error);
            throw error;
        } finally {
            client.release();
        }
    }

    /**
     * Ambil semua seller (untuk statistik admin)
     */
    async findAllSellers() {
        const client = await pool.connect();
        
        try {
            const query = `
                SELECT 
                    s.id,
                    s.user_id,
                    s.shop_name,
                    s.shop_description,
                    s.pic_name,
                    s.pic_phone_number,
                    s.pic_email,
                    s.pic_ktp_number,
                    s.pic_address,
                    s.pic_rt,
                    s.pic_rw,
                    s.pic_province,
                    s.pic_city,
                    s.pic_district,
                    s.pic_village,
                    s.pic_photo_path,
                    s.pic_ktp_path,
                    s.status,
                    s.verified_at,
                    s.created_at,
                    s.updated_at,
                    u.email as user_email,
                    u.role
                FROM sellers s
                INNER JOIN users u ON s.user_id = u.id
                ORDER BY s.created_at DESC
            `;

            const result = await client.query(query);
            return result.rows;

        } catch (error) {
            console.error("FindAllSellers Error:", error);
            throw error;
        } finally {
            client.release();
        }
    }

    /**
     * Ambil seller berdasarkan province
     */
    async findByProvince(province) {
        const client = await pool.connect();
        
        try {
            const values = Array.isArray(province)
                ? province.map(p => (p || '').trim()).filter(p => p.length)
                : [(province || '').trim()].filter(p => p.length);

            let query = `
                SELECT 
                    s.id,
                    s.user_id,
                    s.shop_name,
                    s.shop_description,
                    s.pic_name,
                    s.pic_phone_number,
                    s.pic_email,
                    s.pic_ktp_number,
                    s.pic_address,
                    s.pic_rt,
                    s.pic_rw,
                    s.pic_province,
                    s.pic_city,
                    s.pic_district,
                    s.pic_village,
                    s.pic_photo_path,
                    s.pic_ktp_path,
                    s.status,
                    s.verified_at,
                    s.created_at,
                    s.updated_at,
                    u.email as user_email,
                    u.role
                FROM sellers s
                INNER JOIN users u ON s.user_id = u.id
                WHERE s.status = 'approved'
            `;

            const params = [];

            if (values.length > 0 && values[0].toLowerCase() !== 'all' && values[0] !== '*') {
                const placeholders = values.map((_, i) => `UPPER($${i + 1})`).join(', ');
                query += ` AND UPPER(s.pic_province) IN (${placeholders})`;
                params.push(...values);
            }

            query += ` ORDER BY s.shop_name ASC`;

            const result = await client.query(query, params);
            return result.rows;

        } catch (error) {
            console.error("SellerRepository - findByProvince Error:", error);
            throw error;
        } finally {
            client.release();
        }
    }

    /**
     * UPDATE STATUS - Untuk Approve/Reject Seller
     */
    async updateStatus(sellerId, status, verifiedAt = null, rejectionReason = null) {
        const client = await pool.connect();
        
        try {
            console.log(`SellerRepository.updateStatus(${sellerId}, ${status}) called`);
            
            const query = `
                UPDATE sellers 
                SET status = $1, 
                    verified_at = $2,
                    rejection_reason = $3,
                    updated_at = NOW()
                WHERE id = $4
                RETURNING *
            `;

            const result = await client.query(query, [
                status, 
                status === 'approved' ? (verifiedAt || new Date()) : null,
                rejectionReason,
                sellerId
            ]);

            if (result.rows.length === 0) {
                throw new Error(`Seller with ID ${sellerId} not found`);
            }

            console.log(`Seller ${sellerId} status updated to ${status}`);
            return result.rows[0];

        } catch (error) {
            console.error("SellerRepository - updateStatus Error:", error);
            throw error;
        } finally {
            client.release();
        }
    }
}

module.exports = SellerRepository;
