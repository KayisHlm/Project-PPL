const UserRepository = require("../../repositories/userRepository");
const Login = require("../../../usecases/auth/login");
const { 
    BadRequest, 
    CredentialNotFulfilled, 
    NotFound 
} = require("../../../domain/errors");

async function LoginController(req, res) {
    const { email, password } = req.body;

    try {
        console.log("[LoginController] Login attempt for:", email);
        
        const userRepository = new UserRepository();
        const login = new Login(userRepository);
        
        // Eksekusi login
        const result = await login.execute(email, password);

        console.log("[LoginController] Login successful for:", email);
        console.log("[LoginController] Result:", result);  // ← TAMBAH LOG INI

        if (!result.token || !result.user) {
            console.error("[LoginController] ERROR: Token or user missing!");
            console.error("[LoginController] Result:", result);
            throw new Error("Token or user data missing from login result");
        }

        // Return response
        return res.status(200).json({
            code: 200,
            message: "Login successful",
            token: result.token,
            user: result.user
        });

    } catch (error) {
        console.error("[LoginController] Error:", error.message);
        console.error("[LoginController] Stack:", error.stack);
        
        if (error instanceof BadRequest) {
            return res.status(400).json({
                code: 400,
                message: error.message
            });
        }

        if (error instanceof NotFound) {
            return res.status(404).json({
                code: 404,
                message: error.message
            });
        }

        if (error instanceof CredentialNotFulfilled) {
            return res.status(401).json({
                code: 401,
                message: error.message
            });
        }

        return res.status(500).json({
            code: 500,
            message: "Internal Server Error",
            error: error.message
        });
    }
}

module.exports = LoginController;