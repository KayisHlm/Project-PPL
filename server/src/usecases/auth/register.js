
const { BadRequest, AlreadyExist } = require("../../domain/errors");

class Register {
    constructor(userRepository) {
        this.userRepository = userRepository;
    }

    async execute(userData) {
        // Basic required fields for registration
        const requiredFields = [
            "email",
            "password",
            "shopName",
            "picName",
            "picPhoneNumber",
            "picEmail",
            "picAddress",
            "picRt",
            "picRw",
            "picVillage",
            "picCity",
            "picProvince",
            "picKtpNumber"
        ];

        for (const field of requiredFields) {
            if (!userData[field] && userData[field] !== 0) {
                throw new BadRequest(`Field '${field}' is required for registration`);
            }
        }

        // Simple email validation for account email and PIC email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(userData.email)) {
            throw new BadRequest("Invalid email format for 'email'");
        }
        if (!emailRegex.test(userData.picEmail)) {
            throw new BadRequest("Invalid email format for 'picEmail'");
        }

        // Password minimum length
        if (typeof userData.password !== 'string' || userData.password.length < 6) {
            throw new BadRequest('Password must be at least 6 characters long');
        }

        // Basic numeric validations
        const numericFields = ["picPhoneNumber", "picRt", "picRw", "picKtpNumber"];
        for (const nf of numericFields) {
            const val = String(userData[nf]);
            if (!/^[0-9]+$/.test(val)) {
                throw new BadRequest(`Field '${nf}' must contain only digits`);
            }
        }

        // All validations passed — attempt to create user+seller
        try {
            const created = await this.userRepository.create(userData);
            return created;
        } catch (err) {
            // Map Postgres unique violation to AlreadyExist
            if (err && (err.code === '23505' || /duplicate key|unique constraint/i.test(err.message || ''))) {
                throw new AlreadyExist('A resource with the same unique value already exists (email / pic_email / ktp number)');
            }
            throw err;
        }
    }
}

module.exports = Register;