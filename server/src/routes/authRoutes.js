const express = require("express");
const router = express.Router();
const RegisterController = require("../infrastructure/controllers/auth/registerController"); 

router.post("/create", RegisterController);

module.exports = router