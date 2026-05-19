# eFarmar - Agriculture Web Application

A modern agriculture web application built with Laravel MVC framework.

## Prerequisites
- PHP >= 8.1
- Composer
- MySQL
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
# DB_DATABASE=efarmar
# DB_USERNAME=root
# DB_PASSWORD=yourpassword

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
- Email: admin@efarmar.com
- Password: password
