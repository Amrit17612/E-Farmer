# E-Farmer - Agriculture Web Application

A smart agriculture platform that helps farmers manage farming activities and access digital agricultural solutions through a user-friendly Laravel web application.

## Prerequisites
- PHP >= 8.1
- Composer
- MongoDB
- Node.js & NPM

## Installation

```bash
# Clone & navigate
cd E-farmer

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file
# DB_CONNECTION=mongodb
# DB_HOST=127.0.0.1
# DB_PORT=27017
# DB_DATABASE=efarmar

# Run migrations & seed
php artisan migrate --seed

# Start development server
php artisan serve
```

## Project Structure
```
efarmar/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php
│   │   ├── CropController.php
│   │   ├── MarketController.php
│   │   ├── OrderController.php
│   │   └── SchemeController.php
│   └── Models/
│       ├── User.php
│       ├── Crop.php
│       ├── Order.php
│       ├── MarketPrice.php
│       └── Scheme.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/views/
│   ├── layouts/
│   ├── auth/
│   ├── dashboard/
│   ├── crops/
│   ├── market/
│   ├── orders/
│   ├── schemes/
│   └── weather/
└── routes/web.php
```

## Default Credentials (after seeding)
- Email: admin@gmail.com
- Password: admin123
