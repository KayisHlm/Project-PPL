require("dotenv").config();
const express = require("express");
const cors = require("cors");
const cookieParser = require("cookie-parser");

const app = express();
const port = process.env.PORT || 3001; // 

const origins = [
  "http://localhost:3000",
  "http://localhost:8000"
];

app.use(cors({
  origin: origins,
  methods: ["GET", "POST"],
  credentials: true
}));

app.use(express.json());
app.use(cookieParser());

// import Routes
const authRoutes = require("./src/routes/authRoutes");

// buat endpoint
app.use("/api/auth", authRoutes);

// check health
app.get("/api/health", (req, res) => {
  res.status(200).json({
    message: "Server is running successfully",
    timestamp: new Date().toISOString(),
  });
});

app.listen(port, () => {
  console.log(`Server jalan di http://localhost:${port}`);
});