const UserRepositoryInterface = require("../../domain/interface/userRepositoryInterface");
const pool = require("../../db");
const User = require("../../domain/entities/user");
const Seller = require("../../domain/entities/seller");
const { hashPassword } = require("../middleware/bcrypt");

class UserRepository extends UserRepositoryInterface {
    async create(userData) {
        const { 
            email, 
            password, 
            shopName, 
            shopDescription, 
            picName, 
            picPhoneNumber, 
            picEmail, 
            picAddress, 
            picRt, 
            picRw, 
            picProvince, 
            picCity, 
            picDistrict, 
            picVillage, 
            picKtpNumber, 
            picPhotoPath, 
            picKtpPath 
        } = userData;
        
        const hashedPassword = await hashPassword(password);
        const client = await pool.connect();

        try {
            await client.query('BEGIN');

            // Insert ke tabel users
            const userQuery = `
                INSERT INTO users (email, password, role, created_at, updated_at)
                VALUES ($1, $2, $3, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
                RETURNING *
            `;
            const userValues = [email, hashedPassword, 'seller'];
            const userResult = await client.query(userQuery, userValues);
            const newUser = userResult.rows[0];

            // Insert ke tabel sellers
            const sellerQuery = `
                INSERT INTO sellers (
                    user_id, shop_name, shop_description, pic_name, pic_phone_number, 
                    pic_email, pic_address, pic_rt, pic_rw, pic_province, pic_city, pic_district, pic_village, 
                    pic_ktp_number, pic_photo_path, 
                    pic_ktp_path, status, created_at, updated_at
                )
                VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16, $17, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
                RETURNING *
            `;
            const sellerValues = [
                newUser.id,
                shopName,
                shopDescription || null,
                picName,
                picPhoneNumber,
                picEmail,
                picAddress,
                picRt,
                picRw,
                picProvince,
                picCity,
                picDistrict,
                picVillage,
                picKtpNumber,
                picPhotoPath || null,
                picKtpPath || null,
                'pending'
            ];
            const sellerResult = await client.query(sellerQuery, sellerValues);
            const newSeller = sellerResult.rows[0];

            await client.query('COMMIT');

            // Return object gabungan User dan Seller
            return {
                user: new User(
                    newUser.id,
                    newUser.email,
                    newUser.password,
                    newUser.role,
                    newUser.created_at,
                    newUser.updated_at
                ),
                seller: new Seller({
                id: newSeller.id,
                userId: newSeller.user_id,
                shopName: newSeller.shop_name,
                shopDescription: newSeller.shop_description,
                picName: newSeller.pic_name,
                picPhoneNumber: newSeller.pic_phone_number,
                picEmail: newSeller.pic_email,
                picAddress: newSeller.pic_address,
                picRt: newSeller.pic_rt,
                picRw: newSeller.pic_rw,
                picProvince: newSeller.pic_province,
                picCity: newSeller.pic_city,
                picDistrict: newSeller.pic_district,
                picVillage: newSeller.pic_village,
                picKtpNumber: newSeller.pic_ktp_number,
                picPhotoPath: newSeller.pic_photo_path,
                picKtpPath: newSeller.pic_ktp_path,
                status: newSeller.status,
                rejectionReason: newSeller.rejection_reason,
                verifiedAt: newSeller.verified_at,
                createdAt: newSeller.created_at,
                updatedAt: newSeller.updated_at
                })
            };
        } catch (error) {
            await client.query('ROLLBACK');
            throw error;
        } finally {
            client.release();
        }
    }

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

    async findById(userId) {
        const client = await pool.connect();
        
        try {
            const query = `
                SELECT 
                    u.id as user_id,
                    u.email,
                    u.role,
                    u.created_at,
                    s.pic_name as name,
                    s.pic_phone_number as phone,
                    s.id as seller_id,
                    s.shop_name,
                    s.shop_description,
                    s.pic_address as shop_address,
                    s.pic_city as city,
                    s.pic_province as province,
                    s.pic_phone_number as shop_phone,
                    s.status as seller_status
                FROM users u
                LEFT JOIN sellers s ON u.id = s.user_id
                WHERE u.id = $1
            `;
            
            const result = await client.query(query, [userId]);
            
            if (result.rows.length === 0) {
                return null;
            }
            
            return result.rows[0];
        } catch (error) {
            console.error("Error in findById:", error);
            throw error;
        } finally {
            client.release();
        }
    }

    async findByEmail(email) {
        const query = `
            SELECT 
                u.id,
                u.email,
                u.password,
                u.role,
                u.created_at,
                u.updated_at,
                s.id as seller_id,
                s.shop_name,
                s.shop_description,
                s.pic_name,
                s.pic_phone_number,
                s.pic_email,
                s.status as seller_status
            FROM users u
            LEFT JOIN sellers s ON u.id = s.user_id
            WHERE u.email = $1
        `;
        
        try {
            const result = await pool.query(query, [email]);
            
            // Kalau tidak ada user dengan email ini
            if (result.rows.length === 0) {
                return null;
            }
            
            // Return user pertama 
            return result.rows[0];
        } catch (error) {
            console.error('[UserRepository] Error finding user by email:', error);
            throw error;
        }
    }

    async update(userId, updateData) {
        const { 
            email,
            password,
            shopName, 
            shopDescription, 
            picName, 
            picPhoneNumber, 
            picEmail, 
            picAddress, 
            picRt, 
            picRw, 
            picVillage, 
            picCity, 
            picProvince, 
            picKtpNumber, 
            picPhotoPath, 
            picKtpPath 
        } = updateData;
        
        const client = await pool.connect();

        try {
            await client.query('BEGIN');

            // Update tabel users (jika ada email atau password yang diubah)
            if (email || password) {
                const userUpdateFields = [];
                const userUpdateValues = [];
                let userParamCount = 1;

                if (email) {
                    userUpdateFields.push(`email = $${userParamCount++}`);
                    userUpdateValues.push(email);
                }

                if (password) {
                    const hashedPassword = await hashPassword(password);
                    userUpdateFields.push(`password = $${userParamCount++}`);
                    userUpdateValues.push(hashedPassword);
                }

                userUpdateValues.push(userId);

                const userQuery = `
                    UPDATE users 
                    SET ${userUpdateFields.join(', ')}
                    WHERE id = $${userParamCount}
                    RETURNING *
                `;
                
                await client.query(userQuery, userUpdateValues);
            }

            // Update tabel sellers
            const sellerUpdateFields = [];
            const sellerUpdateValues = [];
            let sellerParamCount = 1;

            if (shopName !== undefined) {
                sellerUpdateFields.push(`shop_name = $${sellerParamCount++}`);
                sellerUpdateValues.push(shopName);
            }
            if (shopDescription !== undefined) {
                sellerUpdateFields.push(`shop_description = $${sellerParamCount++}`);
                sellerUpdateValues.push(shopDescription);
            }
            if (picName !== undefined) {
                sellerUpdateFields.push(`pic_name = $${sellerParamCount++}`);
                sellerUpdateValues.push(picName);
            }
            if (picPhoneNumber !== undefined) {
                sellerUpdateFields.push(`pic_phone_number = $${sellerParamCount++}`);
                sellerUpdateValues.push(picPhoneNumber);
            }
            if (picEmail !== undefined) {
                sellerUpdateFields.push(`pic_email = $${sellerParamCount++}`);
                sellerUpdateValues.push(picEmail);
            }
            if (picAddress !== undefined) {
                sellerUpdateFields.push(`pic_address = $${sellerParamCount++}`);
                sellerUpdateValues.push(picAddress);
            }
            if (picRt !== undefined) {
                sellerUpdateFields.push(`pic_rt = $${sellerParamCount++}`);
                sellerUpdateValues.push(picRt);
            }
            if (picRw !== undefined) {
                sellerUpdateFields.push(`pic_rw = $${sellerParamCount++}`);
                sellerUpdateValues.push(picRw);
            }
            if (picVillage !== undefined) {
                sellerUpdateFields.push(`pic_village = $${sellerParamCount++}`);
                sellerUpdateValues.push(picVillage);
            }
            if (picCity !== undefined) {
                sellerUpdateFields.push(`pic_city = $${sellerParamCount++}`);
                sellerUpdateValues.push(picCity);
            }
            if (picProvince !== undefined) {
                sellerUpdateFields.push(`pic_province = $${sellerParamCount++}`);
                sellerUpdateValues.push(picProvince);
            }
            if (picKtpNumber !== undefined) {
                sellerUpdateFields.push(`pic_ktp_number = $${sellerParamCount++}`);
                sellerUpdateValues.push(picKtpNumber);
            }
            if (picPhotoPath !== undefined) {
                sellerUpdateFields.push(`pic_photo_path = $${sellerParamCount++}`);
                sellerUpdateValues.push(picPhotoPath);
            }
            if (picKtpPath !== undefined) {
                sellerUpdateFields.push(`pic_ktp_path = $${sellerParamCount++}`);
                sellerUpdateValues.push(picKtpPath);
            }

            let sellerResult = null;
            if (sellerUpdateFields.length > 0) {
                sellerUpdateValues.push(userId);

                const sellerQuery = `
                    UPDATE sellers 
                    SET ${sellerUpdateFields.join(', ')}
                    WHERE user_id = $${sellerParamCount}
                    RETURNING *
                `;
                
                sellerResult = await client.query(sellerQuery, sellerUpdateValues);
            }

            // Get updated data
            const userResult = await client.query('SELECT * FROM users WHERE id = $1', [userId]);
            const finalSellerResult = sellerResult || await client.query('SELECT * FROM sellers WHERE user_id = $1', [userId]);

            await client.query('COMMIT');

            const updatedUser = userResult.rows[0];
            const updatedSeller = finalSellerResult.rows[0];

            return {
                user: new User(
                    updatedUser.id,
                    updatedUser.email,
                    updatedUser.role,
                    updatedUser.password,
                    updatedUser.created_at,
                    updatedUser.updated_at
                ),
                seller: new Seller(
                    updatedSeller.id,
                    updatedSeller.user_id,
                    updatedSeller.shop_name,
                    updatedSeller.shop_description,
                    updatedSeller.pic_name,
                    updatedSeller.pic_phone_number,
                    updatedSeller.pic_email,
                    updatedSeller.pic_address,
                    updatedSeller.pic_rt,
                    updatedSeller.pic_rw,
                    updatedSeller.pic_village,
                    updatedSeller.pic_city,
                    updatedSeller.pic_province,
                    updatedSeller.pic_ktp_number,
                    updatedSeller.pic_photo_path,
                    updatedSeller.pic_ktp_path,
                    updatedSeller.status,
                    updatedSeller.rejection_reason,
                    updatedSeller.verified_at,
                    updatedSeller.created_at,
                    updatedSeller.updated_at
                )
            };
        } catch (error) {
            await client.query('ROLLBACK');
            throw error;
        } finally {
            client.release();
        }
    }
}

module.exports = UserRepository;