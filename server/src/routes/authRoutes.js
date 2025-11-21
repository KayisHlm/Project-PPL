const express = require("express");
const router = express.Router();
const RegisterController = require("../infrastructure/controllers/auth/registerController"); 
const LoginController = require("../infrastructure/controllers/auth/loginController");

router.post("/login", LoginController);

router.post("/create", RegisterController);

module.exports = router