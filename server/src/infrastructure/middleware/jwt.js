const jwt = require("jsonwebtoken");

function verifyToken(token) {
    return new Promise((resolve, reject) => {
        jwt.verify(token, process.env.JWT_SECRET, (err, decoded) => {
            if (err) {
                return reject(err);
            }
            resolve(decoded);
        });
    });
}

function generateToken() {
    return jwt.sign(payload, process.env.JWT_SECRET, {expiresIn: "3h"});
}

module.exports = {
    verifyToken,
    generateToken,
};