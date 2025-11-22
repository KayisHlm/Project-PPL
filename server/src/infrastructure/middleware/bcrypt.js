const bcrypt = require("bcrypt");

const saltRounds = parseInt(process.env.BCRYPT_SALT_ROUNDS, 10) || 10;

function hashPassword(password) {
    return bcrypt.hash(password, saltRounds);
}

async function comparePassword(plainPassword, hashedPassword) {
    return bcrypt.compare(plainPassword, hashedPassword);
}

module.exports = { hashPassword, comparePassword };