const GetUserProfile = require("../../../usecases/profile/getUserProfile");
const UserRepository = require("../../repositories/userRepository");

async function GetUserProfileController(req, res) {
    try {
        const userId = req.user.userId;

        console.log(`[GetUserProfileController] Request from user ID: ${userId}`);

        const userRepository = new UserRepository();
        const getUserProfile = new GetUserProfile(userRepository);

        const result = await getUserProfile.execute(userId);

        return res.status(200).json({
            success: true,
            message: "User profile retrieved successfully",
            data: result
        });

    } catch (error) {
        console.error("[GetUserProfileController] Error:", error);
        
        if (error.message === "User not found") {
            return res.status(404).json({
                success: false,
                message: "User not found"
            });
        }

        return res.status(500).json({
            success: false,
            message: "Failed to retrieve user profile",
            error: error.message
        });
    }
}

module.exports = GetUserProfileController;