const rateLimit = require("express-rate-limit");

const loginLimiter = rateLimit({
  windowMs: 2 * 60 * 1000,
  max: 5,
  message: {
    code: 429,
    message: "Terlalu banyak percobaan login. Coba lagi dalam 2 menit.",
  },
  standardHeaders: true,
  legacyHeaders: false,
});

module.exports = { loginLimiter };
