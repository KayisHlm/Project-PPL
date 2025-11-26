const express = require("express");
const router = express.Router();
const RegisterController = require("../infrastructure/controllers/auth/registerController"); 
const LoginController = require("../infrastructure/controllers/auth/loginController");
const LogoutController = require("../infrastructure/controllers/auth/logoutController");

router.post("/login", LoginController);

router.post("/logout", LogoutController);

router.post("/create", RegisterController);

module.exports = router
