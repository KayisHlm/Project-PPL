require("dotenv").config();
const express = require("express");
const cors = require("cors");
const cookieParser = require("cookie-parser");

const adminRoutes = require("./src/routes/adminRoutes");

const app = express();
const port = process.env.PORT || 3001;

const origins = [
  "http://localhost:3000",
  "http://localhost:8000", 
  "http://127.0.0.1:8000"
];

app.use(cors({
  origin: origins,
  methods: ["GET", "POST", "PUT", "DELETE"],
  credentials: true,
  allowedHeaders: ["Content-Type", "Authorization"],
}));

app.use(express.json());
app.use(cookieParser());

// import Routes
const authRoutes = require("./src/routes/authRoutes");
const productRoutes = require("./src/routes/productRoutes");
const categoryRoutes = require("./src/routes/categoryRoutes");
const reviewRoutes = require("./src/routes/reviewRoutes");
const profileRoutes = require("./src/routes/profileRoutes");
const statisticsRoutes = require("./src/routes/statisticsRoutes");

// buat endpoint
app.use("/api/auth", authRoutes);
app.use("/api/admin", adminRoutes);
app.use("/api/products", productRoutes);
app.use("/api/categories", categoryRoutes);
app.use("/api/reviews", reviewRoutes);
app.use("/api/profile", profileRoutes);
app.use("/api/statistics", statisticsRoutes);

// check health
app.get("/api/health", (req, res) => {
  res.status(200).json({
    message: "Server is running successfully",
    timestamp: new Date().toISOString(),
  });
});

// 404 handler
app.use((req, res) => {
    res.status(404).json({
        code: 404,
        message: "Route not found"
    });
});

// Error handling
app.use((err, req, res, next) => {
    console.error("Unhandled Error:", err);
    res.status(500).json({
        code: 500,
        message: "Internal server error",
        error: process.env.NODE_ENV === "development" ? err.message : undefined
    });
});

app.listen(port, () => {
  console.log(`Server jalan di http://localhost:${port}`);
});
