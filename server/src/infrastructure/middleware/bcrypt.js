const bcrypt = require("bcrypt");

const saltRounds = process.env.BCRYPT_SALT_ROUNDS;

function hashPassword(password) {
    return bcrypt.hash(password, saltRounds);
}

module.exports = hashPassword;