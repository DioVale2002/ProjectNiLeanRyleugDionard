# NCB OIMS — New Century Books Order & Inventory Management System

> **CS12L Software Engineering 1 — Capstone Project**
> Laravel 11 · PHP 8.2 · MySQL · Blade · Plain CSS

---

## Table of Contents

- [Project Overview](#project-overview)
- [Team Roles](#team-roles)
- [Prerequisites](#prerequisites)
- [Installation & Setup](#installation--setup)
- [Project Structure](#project-structure)
- [Database Schema](#database-schema)
- [All Routes](#all-routes)
- [Subsystems Built](#subsystems-built)
- [Testing](#testing)
- [Frontend Dev Guide](#frontend-dev-guide)
- [Development Commands](#development-commands)
- [Troubleshooting](#troubleshooting)

---

## Project Overview

NCB OIMS is a bookstore management system for New Century Books. It handles:

- **Customer-facing**: Browse catalog, manage cart, place orders
- **Admin-facing**: Manage products, stock, vouchers, and analytics

Authentication uses an OTP-based email system (no passwords on the customer side in the final version — the current `password` field in customers is a placeholder for the transition).

---

## Team Roles

| Role | Responsibility |
|------|---------------|
| Backend Dev (Dio) | Laravel backend — controllers, models, migrations, routes, tests |
| Frontend Dev | Blade view styling — CSS, layout, Figma implementation |

> **Frontend dev:** Jump straight to the [Frontend Dev Guide](#frontend-dev-guide) section.

---

## Prerequisites

Make sure you have these installed:

- PHP 8.2+
- Composer
- MySQL 8+
- Node.js (for Tailwind/Vite if needed)
- Git

---

## Installation & Setup

### 1. Clone the repo

```bash
git clone https://github.com/DioVale2002/ProjectNiLeanRyleugDionard.git
cd ProjectNiLeanRyleugDionard
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Set up environment

```bash
cp .env .env.example
```

Open `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ncb_oims
DB_USERNAME=ncb_user
DB_PASSWORD=password
```

### 4. Create the database

```bash
sudo mysql -u root
```

Inside MySQL:

```sql
CREATE DATABASE ncb_oims;
CREATE USER 'ncb_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON ncb_oims.* TO 'ncb_user'@'localhost';
GRANT ALL PRIVILEGES ON testing.* TO 'ncb_user'@'localhost';
CREATE DATABASE IF NOT EXISTS testing;
FLUSH PRIVILEGES;
EXIT;
```

### 5. Generate app key and run migrations

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### 6. Start the server

```bash
php artisan serve
```

App runs at `http://localhost:8000`.

---

## Project Structure

```
ProjectNiLeanRyleugDionard/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── ProductController.php     # Admin product CRUD
│   │   │   │   ├── StockController.php       # Stock in/out management
│   │   │   │   └── VoucherController.php     # Voucher CRUD
│   │   │   ├── AccountController.php         # Customer account pages
│   │   │   ├── AuthController.php            # Login, register, logout
│   │   │   ├── CatalogController.php         # Public product browsing
│   │   │   ├── CartController.php            # Cart CRUD
│   │   │   └── OrderController.php           # Order placement
│   │   └── Requests/
│   │       ├── Admin/                        # Admin form validation
│   │       ├── PlaceOrderRequest.php
│   │       └── StoreCartItemRequest.php
│   └── Models/
│       ├── Address.php
│       ├── Archive.php
│       ├── Cart.php
│       ├── CartItem.php
│       ├── Customer.php
│       ├── Order.php
│       ├── PaymentMethod.php
│       ├── Product.php
│       ├── Sale.php
│       ├── StockIn.php
│       ├── StockOut.php
│       └── Voucher.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── PaymentMethodSeeder.php           # Seeds GCash, Maya, COD, Bank Transfer
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── layouts/app.blade.php         # Admin layout (sidebar + topbar)
│       │   ├── products/                     # index, create, edit, show
│       │   ├── stock/                        # index
│       │   └── vouchers/                     # index, create, edit
│       ├── auth/
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   └── welcome.blade.php
│       ├── account/
│       │   ├── orders.blade.php
│       │   ├── archived.blade.php
│       │   ├── addresses.blade.php
│       │   └── security.blade.php
│       ├── catalog/
│       │   ├── index.blade.php               # Product grid + search + genre filter
│       │   └── show.blade.php                # Product detail + add to cart
│       └── cart/
│           └── index.blade.php               # Cart items + checkout form
├── public/
│   ├── css/
│   │   ├── header.css                        # Shared header styles
│   │   ├── userAccount.css                   # Account page styles
│   │   ├── admin.css                         # Admin panel styles
│   │   ├── catalog.css                       # TODO: frontend dev styles this
│   │   └── cart.css                          # TODO: frontend dev styles this
│   └── images/                               # Logo, icons
├── routes/
│   └── web.php
└── tests/
    └── Feature/
        ├── AuthTest.php                      # 10 tests
        ├── AccountTest.php                   # 18 tests
        ├── InventoryTest.php                 # 23 tests
        ├── CatalogTest.php                   # 9 tests
        └── CartTest.php                      # 19 tests
```

---

## Database Schema

### customers
| Column | Type | Notes |
|--------|------|-------|
| cus_id | BIGINT PK | |
| first_name | VARCHAR | |
| last_name | VARCHAR | |
| contact_num | VARCHAR | |
| email | VARCHAR | unique |
| password | VARCHAR | hashed |
| remember_token | VARCHAR | |

### addresses
| Column | Type | Notes |
|--------|------|-------|
| add_id | BIGINT PK | |
| country | VARCHAR | nullable |
| province | VARCHAR | nullable |
| city | VARCHAR | nullable |
| barangay | VARCHAR | nullable |
| zip_postal_code | VARCHAR | nullable |
| cus_id | BIGINT FK | → customers |

### products
| Column | Type | Notes |
|--------|------|-------|
| product_ID | BIGINT PK | |
| Title | VARCHAR | |
| Author | VARCHAR | |
| Price | DECIMAL(10,2) | |
| Stock | INT | |
| ISBN | VARCHAR | |
| Publisher | VARCHAR | |
| Genre | VARCHAR | |
| Rating | DECIMAL(3,2) | nullable |
| Review | TEXT | nullable |
| Age_Group | VARCHAR | nullable |
| Length | INT | nullable |
| Width | INT | nullable |

### vouchers
| Column | Type | Notes |
|--------|------|-------|
| voucher_id | BIGINT PK | |
| voucherName | VARCHAR | |
| voucherType | VARCHAR | `percentage` or `flat` |
| voucherAmount | INT | |
| voucherUsed | INT | default 0 |

### carts
| Column | Type | Notes |
|--------|------|-------|
| cart_id | BIGINT PK | |
| createdDate | DATE | |
| status | ENUM | `active`, `checked_out`, `abandoned` |
| cus_id | BIGINT FK | → customers |

### cart_items
| Column | Type | Notes |
|--------|------|-------|
| cartitems_id | BIGINT PK | |
| quantity | INT | |
| unitPrice | DECIMAL(10,2) | |
| subtotal | DECIMAL(10,2) | |
| cart_id | BIGINT FK | → carts |
| product_ID | BIGINT FK | → products |

### orders
| Column | Type | Notes |
|--------|------|-------|
| order_id | BIGINT PK | |
| order_status | ENUM | `Pending`, `Processing`, `Completed`, `Cancelled`, `Failed` |
| order_date | DATE | |
| total_price | DECIMAL(10,2) | |
| voucher_id | BIGINT FK | → vouchers, nullable |
| add_id | BIGINT FK | → addresses, nullable |
| paymentMethod_id | BIGINT FK | → payment_methods |
| cus_id | BIGINT FK | → customers |
| cart_id | BIGINT FK | → carts |

### payment_methods
| Column | Type | Notes |
|--------|------|-------|
| paymentMethod_id | BIGINT PK | |
| methodName | VARCHAR | Cash on Delivery, GCash, Maya, Bank Transfer |

### stock_in
| Column | Type | Notes |
|--------|------|-------|
| stockIn_id | BIGINT PK | |
| stockIn_date | DATE | |
| productIn | BIGINT FK | → products |

### stock_out
| Column | Type | Notes |
|--------|------|-------|
| stockOut_id | BIGINT PK | |
| stockOut_date | DATE | |
| productOut | BIGINT FK | → products |

### archive
| Column | Type | Notes |
|--------|------|-------|
| archived_id | BIGINT PK | |
| archived_date | DATE | |
| archivedProduct | BIGINT FK | → products |

### sales
| Column | Type | Notes |
|--------|------|-------|
| sales_item_id | BIGINT PK | |
| order_id | BIGINT FK | → orders |
| product_id | BIGINT FK | → products |
| quantity | INT | |
| total_price | DECIMAL(10,2) | |

---

## All Routes

### Guest Routes (no login required)

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/` | Redirects to `/login` |
| GET | `/login` | Login page |
| POST | `/login` | Process login |
| GET | `/register` | Register page |
| POST | `/register` | Process registration |
| GET | `/catalog` | Browse all books (supports `?search=` and `?genre=`) |
| GET | `/catalog/{product}` | Product detail page |

### Authenticated Customer Routes (`auth:customer`)

| Method | URI | Description |
|--------|-----|-------------|
| POST | `/logout` | Logout |
| GET | `/dashboard` | Redirects to `/account/orders` |
| GET | `/account/orders` | Active orders |
| GET | `/account/archived` | Completed/cancelled orders |
| GET | `/account/addresses` | Manage delivery address |
| GET | `/account/security` | Account info & password |
| PUT | `/account/address/update` | Update address |
| PUT | `/account/info/update` | Update name, email, password |
| DELETE | `/account/delete` | Delete account |
| GET | `/cart` | View cart |
| POST | `/cart/add` | Add item to cart |
| PATCH | `/cart/update/{cartItem}` | Update item quantity |
| DELETE | `/cart/remove/{cartItem}` | Remove item from cart |
| POST | `/orders` | Place order |

### Admin Routes (no auth middleware yet — to be added)

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/admin/products` | Product list |
| GET | `/admin/products/create` | Add product form |
| POST | `/admin/products` | Save new product |
| GET | `/admin/products/{product}` | Product detail |
| GET | `/admin/products/{product}/edit` | Edit product form |
| PUT | `/admin/products/{product}` | Update product |
| DELETE | `/admin/products/{product}` | Archive product |
| GET | `/admin/stock` | Stock management |
| POST | `/admin/stock/in` | Record stock increase |
| POST | `/admin/stock/out` | Record stock decrease |
| GET | `/admin/vouchers` | Voucher list |
| GET | `/admin/vouchers/create` | Add voucher form |
| POST | `/admin/vouchers` | Save new voucher |
| GET | `/admin/vouchers/{voucher}/edit` | Edit voucher form |
| PUT | `/admin/vouchers/{voucher}` | Update voucher |
| DELETE | `/admin/vouchers/{voucher}` | Delete voucher |

---

## Subsystems Built

### ✅ Subsystem 1 — Inventory Management (Admin)
- Product CRUD with archiving (products are never hard deleted)
- Stock in/out logging — every change is recorded in `stock_in` / `stock_out`
- Voucher CRUD (percentage and flat discount types)
- Admin layout with sidebar navigation

### ✅ Subsystem 2 — Order & Catalog
- Public catalog with search (by title/author) and genre filtering
- Product detail page with add-to-cart
- Cart with quantity updates and item removal
- Checkout with payment method, delivery address, and optional voucher
- Voucher discount applied at order time (percentage or flat)
- Stock auto-decremented on order placement
- Sale records created per order item

### ⬜ Subsystem 3 — Payment & Billing
> Not yet built. Planned: PDF receipt generation using `barryvdh/laravel-dompdf`.

### ⬜ Subsystem 4 — Report & Analytics
> Not yet built. Planned: Daily/monthly/yearly sales charts, top/low selling books.

---

## Testing

### Setup

Make sure the `testing` database exists and `ncb_user` has access:

```bash
sudo mysql -u root -e "CREATE DATABASE IF NOT EXISTS testing; GRANT ALL PRIVILEGES ON testing.* TO 'ncb_user'@'localhost'; FLUSH PRIVILEGES;"
```

Make sure `.env.testing` exists:

```bash
cp .env .env.testing
# Change DB_DATABASE=testing in .env.testing
```

### Running Tests

```bash
# Run all tests
php artisan test

# Run a specific file
php artisan test tests/Feature/InventoryTest.php
php artisan test tests/Feature/CatalogTest.php
php artisan test tests/Feature/CartTest.php

# Run all feature tests together
php artisan test tests/Feature/
```

### Test Coverage

| File | Tests | What's covered |
|------|-------|---------------|
| `AuthTest.php` | 10 | Register, login, logout, guest redirect |
| `AccountTest.php` | 18 | Orders, addresses, security, account update/delete |
| `InventoryTest.php` | 23 | Product CRUD, archiving, voucher CRUD, stock in/out |
| `CatalogTest.php` | 9 | Browsing, search, genre filter, product detail, auth gates |
| `CartTest.php` | 19 | Cart access, add/update/remove items, place order, voucher discounts |
| **Total** | **79** | |

---

## Frontend Dev Guide

> This section is written specifically for the frontend developer joining the project.

### How the views work

All views are **Blade templates** (Laravel's templating engine). The file extension is `.blade.php`. Blade is just HTML with some extra syntax — you don't need to know Laravel to style these.

### Key Blade syntax you'll encounter

```blade
{{-- This is a comment --}}

{{-- Print a variable (auto-escaped) --}}
{{ $product->Title }}

{{-- Generate a URL to a named route --}}
{{ route('catalog.index') }}
{{ route('catalog.show', $product) }}

{{-- CSRF token — required in every form, don't remove it --}}
@csrf

{{-- Method spoofing — required for PUT/PATCH/DELETE forms --}}
@method('PUT')
@method('DELETE')

{{-- Conditionals --}}
@if(session('success'))
    <div class="alert">{{ session('success') }}</div>
@endif

{{-- Loops --}}
@foreach($products as $product)
    <div>{{ $product->Title }}</div>
@endforeach

{{-- Auth check --}}
@auth('customer')
    <p>You are logged in</p>
@else
    <a href="{{ route('login') }}">Login</a>
@endauth
```

### CSS files — what's done, what's yours

| File | Status | Notes |
|------|--------|-------|
| `public/css/header.css` | Existing | Shared header — already styled, matches existing pages |
| `public/css/userAccount.css` | Existing | Account pages sidebar and layout |
| `public/css/admin.css` | Existing | Admin panel — basic functional styles |
| `public/css/catalog.css` | **Yours** | Catalog index and product detail — see Figma |
| `public/css/cart.css` | **Yours** | Cart page and checkout form — see Figma |

Link your CSS files at the top of the relevant views — the `TODO` comments mark exactly where:

```html
{{-- TODO: Frontend dev links catalog.css here --}}
```

### Class naming conventions

Every element in the catalog and cart views has a descriptive class. Here's the key ones:

**Catalog index (`/catalog`)**
```
.catalog-main          — main content wrapper
.catalog-header        — title + count row
.catalog-title         — page heading
.catalog-count         — "X books found" text
.product-grid          — the book card grid
.product-card          — individual book card
.product-card-link     — wraps the cover + info (clickable)
.product-cover         — cover image area
.product-cover-placeholder  — 📖 emoji placeholder (swap for <img> later)
.badge                 — label on card (e.g. low stock)
.badge-low-stock       — orange/red low stock indicator
.product-card-body     — text content inside card
.product-title         — book title
.product-author        — author name
.product-genre         — genre tag
.product-rating        — star rating
.product-price         — price in ₱
.btn-add-to-cart       — add to cart button on card
.btn-login-to-buy      — shown to guests instead of add to cart
.empty-state           — shown when no results
.pagination-wrapper    — wraps Laravel pagination links
```

**Catalog show (`/catalog/{id}`)**
```
.product-detail-main      — page wrapper
.back-link                — ← Back to Catalog link
.product-detail-layout    — two-column layout (cover left, info right)
.product-detail-cover     — left column
.product-cover-placeholder-lg  — large cover placeholder
.product-detail-info      — right column
.detail-title             — book title (h1)
.detail-author            — "by Author Name"
.detail-price             — price
.detail-rating            — rating display
.detail-meta-table        — specs table (genre, publisher, ISBN, etc.)
.in-stock                 — green stock text
.out-of-stock             — red stock text
.detail-description       — review/description block
.detail-cart-form         — add to cart form
.qty-selector             — quantity input row
.btn-add-to-cart          — add to cart button
.out-of-stock-msg         — shown when stock = 0
```

**Cart (`/cart`)**
```
.cart-main                — page wrapper
.cart-title               — "My Cart" heading
.cart-layout              — two-column: items left, summary right
.cart-items-section       — left column
.cart-item-count          — "X item(s)" label
.cart-item                — individual cart row
.cart-item-cover          — book thumbnail area
.cart-cover-placeholder   — placeholder emoji
.cart-item-info           — title, author, price
.cart-item-title          — book title
.cart-item-author         — author
.cart-item-price          — unit price
.cart-item-controls       — quantity form + subtotal + remove
.qty-form                 — update quantity form
.qty-control              — input + update button row
.btn-update               — update quantity button
.cart-item-subtotal       — item total price
.btn-remove               — remove item button
.cart-summary             — right column (order summary)
.summary-title            — "Order Summary" heading
.summary-breakdown        — price rows
.summary-row              — individual price line
.summary-total-row        — final total row
.checkout-form            — the order placement form
.checkout-field           — individual form field group
.address-box              — displays saved address
.btn-link-sm              — "Change address" link
.no-address-warning       — shown when no address saved
.btn-place-order          — final submit button
.empty-cart               — shown when cart is empty
```

### Book cover images

Cover images aren't implemented yet. Every view has a placeholder:

```html
<div class="product-cover-placeholder">📖</div>
{{-- Future: <img src="{{ $product->cover_image }}" alt="{{ $product->Title }}"> --}}
```

When the backend adds image upload support, you just swap the `<div>` for the `<img>` tag. The surrounding structure stays the same.

### Figma reference

The Figma file has the full design for all screens. Key screens to implement:

- Catalog index — product grid with filters, search bar, genre nav tabs
- Product detail — two-column layout, book specs, add to cart
- Cart — item list with quantity controls, order summary sidebar with checkout form
- Admin dashboard — sidebar nav, product/stock/voucher tables

### Running the app locally

```bash
# Backend dev will have this running, you just need the URL
php artisan serve
# App at http://localhost:8000
```

Visit these URLs to see your views live:

| URL | View |
|-----|------|
| `http://localhost:8000/catalog` | Catalog index |
| `http://localhost:8000/catalog/1` | Product detail (if product ID 1 exists) |
| `http://localhost:8000/cart` | Cart (must be logged in) |
| `http://localhost:8000/admin/products` | Admin product list |

### Git workflow

Always work on a feature branch:

```bash
# Create your branch
git checkout -b frontend/catalog-styling

# After making changes
git add .
git commit -m "Style catalog grid and product cards"
git push origin frontend/catalog-styling

# Create a pull request on GitHub when done
```

Never push directly to `main`.

---

## Development Commands

```bash
# Start server
php artisan serve

# Clear all caches (run this if views aren't updating)
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Re-run all migrations from scratch (WARNING: deletes all data)
php artisan migrate:fresh --seed

# Check all registered routes
php artisan route:list

# Run tests
php artisan test

# Regenerate autoloader (run after adding new classes)
composer dump-autoload
```

---

## Troubleshooting

**Views not updating after edits**
```bash
php artisan view:clear
```

**500 error on admin product edit**

Make sure `UpdateProductRequest.php` has this exact ISBN unique rule:
```php
'ISBN' => 'required|string|unique:products,ISBN,' . $this->route('product')->product_ID . ',product_ID',
```

**`Class StoreCartItemRequest does not exist`**

The file was generated with a typo. Fix it:
```bash
mv app/Http/Requests/StorCartItemRequest.php app/Http/Requests/StoreCartItemRequest.php
```

**Tests failing with `Access denied to database 'testing'`**
```bash
sudo mysql -u root -e "GRANT ALL PRIVILEGES ON testing.* TO 'ncb_user'@'localhost'; FLUSH PRIVILEGES;"
```

**Migration fails with foreign key error**

Check that migration filenames are in chronological order. The customers/addresses migration must run before the orders migration:
```
2025_01_01_000000_create_customers_and_addresses_tables.php  ← must be first
2025_01_02_000000_create_orders_system_tables.php            ← depends on above
```

---

## Stack Reference

| Technology | Version | Purpose |
|------------|---------|---------|
| Laravel | 11.x | Backend framework |
| PHP | 8.2 | Runtime |
| MySQL | 8.x | Database |
| Blade | — | Templating |
| Plain CSS | — | Styling (per-page CSS files) |
| Tailwind CSS | 4.0 (in package.json) | Available but not used yet |

---

*Last updated: April 2026 — Backend subsystems 1 & 2 complete, 79 tests passing*