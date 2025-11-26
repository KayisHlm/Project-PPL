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
            sellerId: decoded.sellerId,
        };
        
        console.log("Authenticated User:", req.user);

        next();
    } catch (error){
        console.error("Authentication Error:", error);
        return res.status(403).json({
            message: "Invalid or expired token.",
        });
    }
}

function authorizeRole(...allowedRoles) {
    return async (req, res, next) => {
        try {
            //pastikan user sudah terautentikasi
            if(!req.user) {
                return res.status(401).json({
                    code: 401,
                    message: "User not authenticated",
                });
            }

            //cek apakah role user ada di allowedRoles
            if(!allowedRoles.includes(req.user.role)) {
                console.log(`Access denied`);

                return res.status(403).json({
                    code: 403,
                    message: `Access denied`,
                });
            }
            
            //Role Sesuai
            console.log(`Access granted for role: ${req.user.role}`);
            next();
        } catch (error) {
            console.error("Authorization Error:", error);
            return res.status(500).json({
                code: 500,
                message: "Internal server error during authorization",
            });
        }
    };
}

//admin
function requireAdmin(req, res, next){
    authorizeRole("platform_admin")(req, res, next);
}

//seller
function requireSeller(req, res, next){
    authorizeRole("seller")(req, res, next);
}

module.exports = {
    authenticateToken,
    authorizeRole,
    requireAdmin,
    requireSeller
}
