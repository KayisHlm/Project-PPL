const UserInformation = require("../../dto/user/userInformation");

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

            // Transform to DTO
            const userInfo = new UserInformation(
                user.user_id,
                user.email,
                user.name,
                user.phone,
                user.role,
                user.created_at
            );

            // Add seller information if exists
            const response = {
                user: {
                    userId: userInfo.userId,
                    email: userInfo.email,
                    name: userInfo.name,
                    phone: userInfo.phone,
                    role: userInfo.role,
                    createdAt: userInfo.createdAt
                },
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
                    status: user.seller_status
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