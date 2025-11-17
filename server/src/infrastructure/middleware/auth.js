const { verifyToken } = require("./jwt");

async function authenticateToken(req, res, next) {
    try{
        // ambil token dari cookies
        let token = req.cookies["access_token"];

        if (!token) {
            // kalo token gada, kita cek di authHeader
            const authHeader = req.headers.authorization;
            if(authHeader && authHeader.startsWith("Bearer ")) {
                // ada, maka potong "Bearer ", jadiin token
                token = authHeader.substring(7);
            }
        }

        // token masih gada
        if (!token) {
            // return response 401
            return res.status(401).json({
                message: "Access token is required",
            });
        }

        // token ada, kita verifikasi dengan middleware JWT, method verifyToken
        const decoded = await verifyToken(token);

        req.user = {
            userId: decoded.userId,
            email: decoded.email,
            role: decoded.role,
        };
        
        console.log("Authenticated User:", req.user);

        next();
    } catch (error){
        console.error("Authentication Error:", error);
        return req.status(403).json({
            message: "Invalid or expired token.",
        });
    }
}

module.exports = {
    authenticateToken,
}