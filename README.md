# MaxMart - Premium E-Commerce Platform

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel Version">
    <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version">
    <img src="https://img.shields.io/badge/Tailwind_CSS-3.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
    <img src="https://img.shields.io/badge/Livewire-3.x-4E56A6?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire">
    <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

## 🛍️ Overview

**MaxMart** is a premium, production-ready e-commerce platform built with Laravel 12, featuring a modern light theme powered by Tailwind CSS. It provides a complete solution for online stores with both storefront and admin panel functionality.

### ✨ Key Features

#### Storefront
- 🏠 Dynamic homepage with customizable sections
- 🛒 Advanced product catalog with filtering & search
- 🎯 Product variations (size, color, attributes)
- 🛍️ Shopping cart with real-time updates
- 💳 Multi-payment gateway support (Stripe, PayPal, SSLCommerz, bKash, Nagad, COD)
- 📦 Order tracking system
- ⭐ Product reviews & ratings
- ❤️ Wishlist functionality
- 🔍 Product comparison
- 📝 Blog system
- 📧 Newsletter subscription
- 🌐 Multi-currency & multi-language support

#### Admin Panel
- 📊 Comprehensive dashboard with analytics
- 📦 Product management with variation generator
- 🏷️ Category & brand management
- 🎨 Attribute management
- 📋 Order management with status tracking
- 👥 Customer management
- 🎫 Coupon & discount system
- ⚡ Flash sale management
- 📝 Blog & CMS page management
- 🍔 Menu builder (mega menu support)
- 🖼️ Media manager
- ⚙️ Settings & appearance customization
- 👨‍💼 Staff & role management
- 📈 Reports & analytics
- 🔍 SEO management
- 💾 Backup system
- 📜 Activity logging

---

## 🚀 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18.x or higher
- npm or yarn
- MySQL 8.0+ or PostgreSQL 14+
- Redis (optional, for caching & sessions)

### Step 1: Clone the Repository

```bash
git clone https://github.com/techliobd/maxmart1.git
cd maxmart1
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### Step 3: Environment Configuration

```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit `.env` file and configure your database and other settings:

```env
APP_NAME=MaxMart
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=maxmart
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration (for order notifications, etc.)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@maxmart.com
MAIL_FROM_NAME="${APP_NAME}"

# Payment Gateway Credentials
STRIPE_KEY=your-stripe-publishable-key
STRIPE_SECRET=your-stripe-secret-key
PAYPAL_CLIENT_ID=your-paypal-client-id
PAYPAL_SECRET=your-paypal-secret
SSLCOMMERZ_STORE_ID=your-store-id
SSLCOMMERZ_STORE_PASS=your-store-pass
BKASH_APP_KEY=your-bkash-app-key
BKASH_APP_SECRET=your-bkash-app-secret
NAGAD_MERCHANT_ID=your-nagad-merchant-id
NAGAD_MERCHANT_SECRET=your-nagad-secret

# Redis (optional)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Step 4: Database Setup

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE maxmart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
php artisan migrate

# Seed the database with demo data
php artisan db:seed
```

### Step 5: Storage Setup

```bash
# Create symbolic link for storage
php artisan storage:link

# Set permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Step 6: Build Assets

```bash
# Development build
npm run dev

# Production build
npm run build
```

### Step 7: Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` to access your store.

---

## 👤 Default Admin Credentials

After seeding the database, use these credentials to access the admin panel:

- **Email:** `admin@maxmart.com`
- **Password:** `password`

**⚠️ IMPORTANT:** Change the default admin password immediately after installation!

---

## 📁 Project Structure

```
maxmart/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin panel controllers
│   │   │   └── Storefront/     # Storefront controllers
│   │   ├── Middleware/         # Custom middleware
│   │   └── Requests/           # Form request validation
│   ├── Livewire/
│   │   ├── Admin/              # Admin Livewire components
│   │   └── Storefront/         # Storefront Livewire components
│   ├── Models/                 # Eloquent models
│   ├── Services/               # Business logic services
│   └── Traits/                 # Reusable traits
├── config/
│   └── maxmart.php             # MaxMart configuration
├── database/
│   ├── factories/              # Model factories
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── public/
│   ├── index.php
│   └── assets/                 # Compiled assets
├── resources/
│   ├── css/                    # Tailwind CSS source
│   ├── js/                     # JavaScript source
│   └── views/
│       ├── admin/              # Admin Blade templates
│       ├── storefront/         # Storefront Blade templates
│       ├── layouts/            # Layout templates
│       └── components/         # Reusable components
├── routes/
│   ├── web.php                 # Storefront routes
│   ├── admin.php               # Admin routes
│   └── api.php                 # API routes
└── storage/
    └── app/public/             # Uploaded files
```

---

## 🗂️ Database Schema

### Core Tables
- `users` - Admin & staff users
- `customers` - Customer accounts
- `customer_addresses` - Customer shipping/billing addresses
- `categories` - Product categories (nested)
- `brands` - Product brands
- `products` - Main products
- `product_images` - Product gallery
- `product_variations` - Product variants (SKU combinations)
- `attributes` - Product attributes (Size, Color, etc.)
- `attribute_values` - Attribute values (S, M, L, Red, Blue, etc.)
- `carts` & `cart_items` - Shopping cart
- `wishlists` & `wishlist_items` - Customer wishlists
- `orders` & `order_items` - Customer orders
- `order_statuses` - Order status history
- `coupons` - Discount coupons
- `reviews` & `review_images` - Product reviews
- `blog_posts` & `blog_categories` - Blog system
- `pages` - CMS pages (About, Contact, etc.)
- `menus` & `menu_items` - Navigation menus
- `settings` - Store settings
- `currencies` - Supported currencies
- `languages` - Supported languages
- `banners` - Homepage banners
- `flash_sales` - Time-limited sales
- `shipping_zones` & `shipping_rates` - Shipping configuration
- `tax_classes` & `tax_rules` - Tax configuration
- `payment_gateways` - Payment methods
- `activity_logs` - System activity tracking
- `notifications` - User notifications

---

## 🛠️ Available Commands

```bash
# Clear all caches
php artisan optimize:clear

# View compiled queries (debugging)
php artisan tinker

# Run tests
php artisan test

# Create backup
php artisan backup:run

# Queue worker (for emails, notifications)
php artisan queue:work

# Schedule runner (for cron jobs)
php artisan schedule:work
```

---

## 🔐 Security Features

- CSRF protection on all forms
- SQL injection prevention via Eloquent ORM
- XSS protection via Blade templating
- Password hashing with bcrypt
- Rate limiting on authentication
- Admin authentication middleware
- Activity logging
- Secure payment gateway integration

---

## 🌐 Multi-Currency & Multi-Language

MaxMart supports multiple currencies and languages out of the box:

### Adding a New Currency
1. Go to Admin → Settings → Currencies
2. Click "Add Currency"
3. Enter currency code (USD, EUR, GBP, etc.), symbol, and exchange rate
4. Set as default if needed

### Adding a New Language
1. Go to Admin → Settings → Languages
2. Click "Add Language"
3. Enter language name, code (en, fr, es, etc.), and flag
4. Set as default if needed

---

## 💳 Payment Gateways

MaxMart supports the following payment methods:

| Gateway | Status | Configuration |
|---------|--------|---------------|
| Stripe | ✅ Ready | Admin → Settings → Payment Gateways |
| PayPal | ✅ Ready | Admin → Settings → Payment Gateways |
| SSLCommerz | ✅ Ready | Admin → Settings → Payment Gateways |
| bKash | ✅ Ready | Admin → Settings → Payment Gateways |
| Nagad | ✅ Ready | Admin → Settings → Payment Gateways |
| Cash on Delivery | ✅ Ready | Admin → Settings → Payment Gateways |

---

## 📦 Shipping Configuration

Configure shipping zones and rates:

1. Go to Admin → Settings → Shipping Zones
2. Create zones (Local, National, International)
3. Add shipping rates per zone (flat rate, weight-based, price-based)
4. Assign zones to customer groups or regions

---

## 🎨 Customization

### Changing Theme Colors

Edit `resources/css/app.css`:

```css
@layer base {
    :root {
        --color-primary: #3B82F6;
        --color-secondary: #10B981;
        --color-accent: #F59E0B;
    }
}
```

### Adding Custom Homepage Sections

1. Go to Admin → Appearance → Homepage Sections
2. Click "Add Section"
3. Choose section type (Featured Products, Categories, Banners, etc.)
4. Configure content and display order

### Modifying Menus

1. Go to Admin → Appearance → Menus
2. Use the drag-and-drop menu builder
3. Add links to categories, pages, or custom URLs
4. Assign menu locations (Header, Footer, Mobile)

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter=ProductTest

# Run with coverage
php artisan test --coverage
```

---

## 📝 License

MaxMart is open-sourced software licensed under the [MIT License](LICENSE).

---

## 🤝 Support

For support, feature requests, or bug reports:
- Email: support@maxmart.com
- GitHub Issues: https://github.com/techliobd/maxmart1/issues
- Documentation: https://maxmart.com/docs

---

## 🙏 Credits

- Built with [Laravel](https://laravel.com)
- Styled with [Tailwind CSS](https://tailwindcss.com)
- Interactive components with [Livewire](https://livewire.laravel.com)
- Icons by [Heroicons](https://heroicons.com)

---

## 📄 Changelog

### Version 1.0.0 (Current)
- Initial release
- Complete e-commerce functionality
- Admin panel with full CRUD operations
- Storefront with modern UI
- Multi-payment gateway support
- Multi-currency & multi-language
- Blog & CMS system
- SEO optimization
- Activity logging
- Backup system

---

Made with ❤️ by the MaxMart Team
