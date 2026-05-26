
require('dotenv').config();
const express = require('express');
const cors = require('cors');
const cookieParser = require('cookie-parser');
const path = 'path';
const fs = require('fs');

const app = express();
const port = process.env.PORT || 3001;

// Middleware
app.use(cors({
  origin: process.env.CORS_ORIGIN,
  credentials: true,
}));
app.use(express.json());
app.use(cookieParser());

// Health check route
app.get('/api', (req, res) => {
  res.status(200).json({ message: 'Server is running!' });
});

// Dynamically load routes from src/routes
const routesPath = ('./src/routes');
fs.readdirSync(routesPath).forEach(file => {
  if (file.endsWith('.js')) {
    const route = require(`${routesPath}/${file}`);
    const routeName = file.split('.')[0].replace('Routes', '').toLowerCase();
    app.use(`/api/${routeName}`, route);
    console.log(`Loaded route: /api/${routeName}`);
  }
});

app.listen(port, () => {
  console.log(`Server is listening on port ${port}`);
});
