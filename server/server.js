require("dotenv").config();
const express = require("express");
const cors = require("cors");

const app = express;
const port = process.env.PORT;

const cookieparser = require("cookie-parser");

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
app.use(cookieparser());

// Import Routes disini
// const nama_route1 = require("path ke route 1");
// const nama_route2 = require("path ke route 2");
// const nama_route3 = require("path ke route 3");
// const nama_route4 = require("path ke route 4");
// const nama_route5 = require("path ke route 5");


// app.use("endpoint1", object routes yang udah diimport #1);
// app.use("endpoint2", object routes yang udah diimport #2);
// app.use("endpoint3", object routes yang udah diimport #3);
// app.use("endpoint4", object routes yang udah diimport #4);
// app.use("endpoint5", object routes yang udah diimport #5);

app.listen(port, () => {
    console.log(`Server jalan di http://localhost:${port}`);
});