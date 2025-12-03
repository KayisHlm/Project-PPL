const { BadRequest, CredentialNotFulfilled, NotFound } = require("../../domain/errors");
const { comparePassword } = require("../../infrastructure/middleware/bcrypt");
const { generateToken } = require("../../infrastructure/middleware/jwt");

class Login {
    constructor(userRepository) {
        this.userRepository = userRepository;
    }

    async execute(email, password) {
        console.log("[Login UseCase] Starting login process for:", email);

        // 1. Validasi input tidak boleh kosong
        if (!email || !password) {
            throw new BadRequest("Email and password are required");
        }

        // 2. Validasi format email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            throw new BadRequest("Invalid email format");
        }

        // 3. Cari user berdasarkan email
        console.log("[Login UseCase] Finding user in database...");
        const user = await this.userRepository.findByEmail(email);
        
        if (!user) {
            console.log("[Login UseCase] User not found");
            throw new NotFound("User with this email does not exist");
        }

        console.log("[Login UseCase] User found, ID:", user.id);

        // 4. Verifikasi password dengan bcrypt
        console.log("[Login UseCase] Verifying password...");
        const isPasswordValid = await comparePassword(password, user.password);
        
        if (!isPasswordValid) {
            console.log("[Login UseCase] Password incorrect");
            throw new CredentialNotFulfilled("Incorrect password");
        }

        console.log("[Login UseCase] Password correct!");

        // 5. Generate JWT Token
        console.log("[Login UseCase] Generating JWT token...");
        const token = generateToken({
            userId: user.id,
            email: user.email,
            role: user.role,
            sellerId: user.seller_id || null
        });

        console.log("[Login UseCase] Token generated successfully");

        // 6. Hapus password dari response (security)
        delete user.password;

        // 7. Return token + user data
        return {
            token : token,
            user: {
                id: user.id,
                email: user.email,
                name: user.pic_name || null,  // Add name field
                role: user.role,
                sellerId: user.seller_id,
                shopName: user.shop_name,
                sellerStatus: user.seller_status
            }
        };
    }
}

module.exports = Login;