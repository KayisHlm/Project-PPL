const UserRepository = require("../../repositories/userRepository");
const Register = require("../../../usecases/auth/register");
const UserInformation = require("../../../dto/user/userInformation");
const { BadRequest, AlreadyExist } = require("../../../domain/errors");


async function RegisterController(req, res) {
    const { 
        email, 
        password, 
        shopName, 
        shopDescription, 
        picName, 
        picPhoneNumber, 
        picEmail, 
        picAddress, 
        picRt, 
        picRw, 
        picVillage, 
        picCity, 
        picProvince, 
        picKtpNumber, 
        picPhotoPath, 
        picKtpPath 
    } = req.body;

    try {
        // [DEBUG]
        console.log("[RegisterController] Incoming body:", req.body);
        
        // inisialisasi objek usecase
        const register = new Register(new UserRepository());
        // eksekusi
        const newUser = await register.execute({
            email, 
            password, 
            shopName, 
            shopDescription, 
            picName, 
            picPhoneNumber, 
            picEmail, 
            picAddress, 
            picRt, 
            picRw, 
            picVillage, 
            picCity, 
            picProvince, 
            picKtpNumber, 
            picPhotoPath, 
            picKtpPath 
        });

        return res.status(201).json({
            code: 201,
            message: "User successfully registered.",
            user: new UserInformation(newUser).toJSON(),
        });
    } catch (error) {
        // [DEBUG]
        console.error("[RegisterController] Error:", error);
        if (
            error instanceof BadRequest ||
            error instanceof AlreadyExist 
        ) {
            return res
            .status(error.statusCode)
            .json({
                code: error.statusCode,
                message: error.message
            });
        } else {
            return res
            .status(500)
            .json({
                code: 500,
                message: "Internal Server Error."
            });
        }
    }

}

module.exports = RegisterController;