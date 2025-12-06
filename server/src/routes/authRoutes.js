const express = require("express");
const router = express.Router();
const RegisterController = require("../infrastructure/controllers/auth/registerController"); 
const LoginController = require("../infrastructure/controllers/auth/loginController");
const LogoutController = require("../infrastructure/controllers/auth/logoutController");
const { loginLimiter } = require("../infrastructure/middleware/rateLimit");

router.post("/login", loginLimiter, LoginController);

router.post("/logout", LogoutController);

router.post("/create", RegisterController);

module.exports = router
