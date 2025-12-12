class GetUserProfile {
    constructor(userRepository) {
        this.userRepository = userRepository;
    }

    async execute(userId) {
        try {
            console.log(`[GetUserProfile] Fetching profile for user ID: ${userId}`);

            const user = await this.userRepository.findById(userId);

            if (!user) {
                throw new Error("User not found");
            }

            const userInfo = {
                userId: user.user_id,
                email: user.email,
                name: user.name,
                phone: user.phone,
                role: user.role,
                createdAt: user.created_at
            };

            // Add seller information if exists
            const response = {
                user: userInfo,
                seller: null
            };

            if (user.seller_id) {
                response.seller = {
                    seller_id: user.seller_id,
                    shop_name: user.shop_name,
                    shop_description: user.shop_description,
                    shop_address: user.shop_address,
                    city: user.city,
                    province: user.province,
                    shop_phone: user.shop_phone,
                    status: user.seller_status,
                    pic_email: user.pic_email,
                    pic_rt: user.pic_rt,
                    pic_rw: user.pic_rw,
                    district: user.district,
                    village: user.village,
                    pic_ktp_number: user.pic_ktp_number,
                    pic_photo_path: user.pic_photo_path,
                    pic_ktp_path: user.pic_ktp_path,
                    verified_at: user.verified_at,
                    created_at: user.seller_created_at,
                    updated_at: user.seller_updated_at
                };
            }

            console.log(`[GetUserProfile] Successfully fetched profile for: ${user.name}`);
            return response;

        } catch (error) {
            console.error("[GetUserProfile] Error:", error);
            throw error;
        }
    }
}

module.exports = GetUserProfile;
