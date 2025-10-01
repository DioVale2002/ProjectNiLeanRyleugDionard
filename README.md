# Bookstore - Laravel Backend

A Laravel-based bookstore application with customer authentication and account management.

---

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Installation & Setup](#installation--setup)
3. [Project Structure](#project-structure)
4. [Available Routes](#available-routes)
5. [Database Schema](#database-schema)
6. [Development Commands](#development-commands)
7. [Testing](#testing)
8. [Styling Guide for Frontend](#styling-guide-for-frontend)
9. [Git Workflow](#git-workflow)
10. [Troubleshooting](#troubleshooting)

---

## Prerequisites

Before you begin, make sure you have the following installed on your system:

- **WSL2** (Windows Subsystem for Linux) - if on Windows
- **Docker Desktop** (with WSL2 integration enabled)
- **Git**

---

## Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/DioVale2002/ProjectNiLeanRyleugDionard.git
cd ProjectNiLeanRyleugDionard
```

### 2. Install Dependencies

```bash
# Install Composer dependencies using Docker
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

### 3. Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Add required Docker variables
echo "WWWGROUP=1000" >> .env
echo "WWWUSER=1000" >> .env
echo "APP_PORT=8000" >> .env

# Edit .env file to configure database
nano .env
```

**Important:** In the `.env` file, find the database section and make sure it looks like this (uncomment if needed):

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

Remove or comment out any SQLite references.

### 4. Generate Application Key

```bash
docker run --rm \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    php artisan key:generate
```

### 5. Start Docker Containers

```bash
# Start all services in the background
docker-compose up -d

# Wait for MySQL to fully initialize
sleep 60

# Verify containers are running
docker-compose ps

# All containers should show "Up (healthy)" status
```

### 6. Run Database Migrations

```bash
# Run migrations to create database tables
docker-compose exec laravel.test php artisan migrate
```

### 7. Access the Application

The application will be running at:
- **Frontend URL**: http://localhost:8000
- **Login Page**: http://localhost:8000/login
- **Register Page**: http://localhost:8000/register

### Common Installation Issues

**Issue: "Connection refused" when running docker-compose**
- **Solution**: Make sure Docker Desktop is running on Windows with WSL2 integration enabled
- Go to Docker Desktop → Settings → Resources → WSL Integration
- Enable integration with your Ubuntu/WSL distro

**Issue: "Port 80 already in use"**
- **Solution**: The project is configured to use port 8000 (check `APP_PORT=8000` in `.env`)

**Issue: MySQL connection errors**
- **Solution**: Make sure your `.env` database settings are uncommented and correct:
  ```env
  DB_CONNECTION=mysql
  DB_HOST=mysql
  DB_PORT=3306
  DB_DATABASE=laravel
  DB_USERNAME=sail
  DB_PASSWORD=password
  ```
- If you started containers before fixing `.env`, recreate them:
  ```bash
  docker-compose down -v
  docker-compose up -d
  sleep 60
  docker-compose exec laravel.test php artisan migrate
  ```

**Issue: "View not found" errors**
- **Solution**: Files might be missing `.blade.php` extension or be in wrong directory
- Check: `ls -la resources/views/auth/` and `ls -la resources/views/account/`

---

## Project Structure

```
bookstore/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php      # Handles login, register, logout
│   │       └── AccountController.php   # Handles account editing
│   └── Models/
│       ├── Customer.php                # Customer model
│       └── Address.php                 # Address model
├── config/
│   └── auth.php                        # Authentication configuration
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   │   ├── login.blade.php        # Login page
│   │   │   └── register.blade.php     # Registration page
│   │   ├── account/
│   │   │   └── edit.blade.php         # Edit account page
│   │   └── dashboard.blade.php        # Dashboard (placeholder)
│   ├── css/
│   │   └── app.css                    # Main CSS file
│   └── js/
│       └── app.js                     # Main JavaScript file
├── routes/
│   └── web.php                        # All application routes
├── database/
│   └── migrations/                    # Database schema
├── tests/
│   └── Feature/                       # Test files
│       ├── AuthTest.php               # Authentication tests
│       └── AccountTest.php            # Account management tests
├── docker-compose.yml                 # Docker configuration
├── .env                               # Environment variables
└── README.md                          # This file
```

---

## Available Routes

### Guest Routes (Not Logged In)
| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | `/` | Redirect | Redirects to login |
| GET | `/register` | `AuthController@showRegister` | Show registration form |
| POST | `/register` | `AuthController@register` | Process registration |
| GET | `/login` | `AuthController@showLogin` | Show login form |
| POST | `/login` | `AuthController@login` | Process login |

### Authenticated Routes (Logged In)
| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | `/dashboard` | View | Show dashboard |
| GET | `/account/edit` | `AccountController@edit` | Show edit account form |
| PUT | `/account/update` | `AccountController@update` | Update account info & address |
| PUT | `/account/password` | `AccountController@updatePassword` | Change password |
| POST | `/logout` | `AuthController@logout` | Logout user |

---

## Database Schema

### Customers Table
| Column | Type | Description |
|--------|------|-------------|
| `cus_id` | BIGINT (PK) | Primary key |
| `first_name` | VARCHAR | Customer's first name |
| `last_name` | VARCHAR | Customer's last name |
| `contact_num` | VARCHAR | Contact number |
| `email` | VARCHAR (unique) | Email address |
| `password` | VARCHAR | Hashed password |
| `remember_token` | VARCHAR | Remember me token |
| `created_at` | TIMESTAMP | Creation timestamp |
| `updated_at` | TIMESTAMP | Last update timestamp |

### Addresses Table
| Column | Type | Description |
|--------|------|-------------|
| `add_id` | BIGINT (PK) | Primary key |
| `country` | VARCHAR | Country |
| `province` | VARCHAR | Province |
| `city` | VARCHAR | City |
| `barangay` | VARCHAR | Barangay |
| `zip_postal_code` | VARCHAR | ZIP/Postal code |
| `cus_id` | BIGINT (FK) | Foreign key to customers |
| `created_at` | TIMESTAMP | Creation timestamp |
| `updated_at` | TIMESTAMP | Last update timestamp |

**Important Notes:**
- Address is **optional** and only appears in the Edit Account page
- Address is **NOT required** during registration
- One customer can have one address (one-to-one relationship)

---

## Development Commands

### Starting/Stopping the Application

```bash
# Start containers (in background)
docker-compose up -d

# Start containers (with logs visible)
docker-compose up

# Stop containers
docker-compose down

# Restart containers
docker-compose restart

# View logs
docker-compose logs -f

# Check container status
docker-compose ps
```

### Running Commands Inside Container

```bash
# Run artisan commands
docker-compose exec laravel.test php artisan [command]

# Clear all caches
docker-compose exec laravel.test php artisan cache:clear
docker-compose exec laravel.test php artisan config:clear
docker-compose exec laravel.test php artisan view:clear
docker-compose exec laravel.test php artisan route:clear

# Run database migrations
docker-compose exec laravel.test php artisan migrate

# Rollback migrations
docker-compose exec laravel.test php artisan migrate:rollback

# Fresh migration (drop all tables and re-migrate)
docker-compose exec laravel.test php artisan migrate:fresh

# Regenerate autoload files
docker-compose exec laravel.test composer dump-autoload

# Access container shell
docker-compose exec laravel.test bash
```

---

## Testing

The project includes comprehensive unit tests covering all authentication and account management features.

### Running Tests

```bash
# Run all tests
docker-compose exec laravel.test php artisan test

# Run specific test file
docker-compose exec laravel.test php artisan test --filter=AuthTest
docker-compose exec laravel.test php artisan test --filter=AccountTest

# Run tests with coverage (if you have Xdebug)
docker-compose exec laravel.test php artisan test --coverage
```

### Test Coverage

**AuthTest.php** (10 tests)
- User can view register page
- User can register with valid data
- User cannot register with invalid email
- User cannot register with duplicate email
- User can view login page
- User can login with correct credentials
- User cannot login with incorrect password
- User can logout
- Guest cannot access dashboard
- Authenticated user can access dashboard

**AccountTest.php** (10 tests)
- Guest cannot access edit account page
- Authenticated user can view edit account page
- User can update account information
- User cannot update email to existing email
- User can add address
- User can update existing address
- User can update password with correct current password
- User cannot update password with incorrect current password
- User cannot update password without confirmation
- Address is optional on update

### Creating the Testing Database

```bash
# Create testing database
docker-compose exec mysql mysql -u sail -ppassword -e "CREATE DATABASE IF NOT EXISTS testing;"
```

---

## Styling Guide for Frontend

All views currently use **basic inline CSS** for rapid prototyping. Here's how to customize them:

### File Locations

| File | Purpose |
|------|---------|
| `resources/views/auth/login.blade.php` | Login form |
| `resources/views/auth/register.blade.php` | Registration form |
| `resources/views/account/edit.blade.php` | Account edit form with address |
| `resources/views/dashboard.blade.php` | Dashboard placeholder |
| `resources/css/app.css` | Main CSS file (compile with Vite) |
| `resources/js/app.js` | Main JavaScript file |

### Blade Template Syntax

```blade
{{-- Comments --}}

{{-- Output escaped data --}}
{{ $variable }}

{{-- Output unescaped data (be careful!) --}}
{!! $html !!}

{{-- CSRF token (REQUIRED in all forms) --}}
@csrf

{{-- Method spoofing for PUT/DELETE --}}
@method('PUT')

{{-- Generate URLs --}}
{{ route('login') }}
{{ route('account.edit') }}

{{-- Old input (persists form data on errors) --}}
{{ old('email') }}

{{-- Conditionals --}}
@if (session('success'))
    <div>{{ session('success') }}</div>
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach
@endif

{{-- Include other views --}}
@include('partials.header')

{{-- Extend layouts --}}
@extends('layouts.app')

@section('content')
    <!-- Your content here -->
@endsection
```

### Adding Your Own Styles

**Option 1: Replace Inline Styles**
Simply remove the `<style>` tags and add your own CSS classes.

**Option 2: Use a CSS Framework**
```bash
# Install Tailwind CSS (example)
docker-compose exec laravel.test npm install -D tailwindcss postcss autoprefixer
docker-compose exec laravel.test npx tailwindcss init -p

# Or install Bootstrap
docker-compose exec laravel.test npm install bootstrap
```

**Option 3: Use Custom CSS**
Edit `resources/css/app.css` and compile with Vite:
```bash
docker-compose exec laravel.test npm run dev
```

---

## Git Workflow

### Basic Commands

```bash
# Check current status
git status

# Check current branch
git branch

# Create and switch to new feature branch
git checkout -b feature/your-feature-name

# Stage changes
git add .

# Commit changes
git commit -m "Your descriptive commit message"

# Push to remote
git push origin feature/your-feature-name

# Pull latest changes
git pull origin main

# Merge main into your branch
git checkout feature/your-feature-name
git merge main
```

### Recommended Workflow

1. Create a feature branch: `git checkout -b feature/styling-login-page`
2. Make your changes
3. Test your changes: `docker-compose exec laravel.test php artisan test`
4. Commit: `git commit -m "Add new login page styling"`
5. Push: `git push origin feature/styling-login-page`
6. Create pull request on GitHub

---

## Troubleshooting

### Docker Issues

**Problem:** `Connection refused` when running docker-compose

**Solution:**
```bash
# Check if Docker is running
docker --version

# Start Docker service (if using Docker in WSL)
sudo service docker start

# OR make sure Docker Desktop is running (Windows)
# Go to Docker Desktop → Settings → Resources → WSL Integration
# Enable integration with your Ubuntu/WSL distro
```

### Database Issues

**Problem:** `Access denied for user 'sail'@'...'` or MySQL connection errors

**Solution:**
```bash
# 1. Make sure .env has correct database settings (uncommented)
cat .env | grep DB_

# Should show:
# DB_CONNECTION=mysql
# DB_HOST=mysql
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=sail
# DB_PASSWORD=password

# 2. Recreate MySQL container with correct credentials
docker-compose down -v
docker-compose up -d
sleep 60
docker-compose exec laravel.test php artisan migrate
```

**Problem:** `SQLSTATE[HY000] [2002] Connection refused`

**Solution:**
```bash
# MySQL is still initializing, wait longer
sleep 30
docker-compose exec laravel.test php artisan migrate

# Check if MySQL is ready
docker-compose logs mysql | tail -20
# Look for: "ready for connections. Version: '8.0.32'"
```

**Problem:** Laravel wants to use SQLite instead of MySQL

**Solution:**
```bash
# Check database configuration
cat .env | grep DB_CONNECTION

# If it shows sqlite, change to mysql
sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env

# Uncomment MySQL settings
nano .env
# Remove # from DB_HOST, DB_PORT, etc.
```

### Container Issues

**Problem:** Port 80 already in use

**Solution:**
```bash
# The project is configured to use port 8000
# Make sure APP_PORT=8000 is in .env
grep APP_PORT .env

# If not there:
echo "APP_PORT=8000" >> .env

# Restart
docker-compose down
docker-compose up -d
```

**Problem:** `vendor/laravel/sail/runtimes/8.4 does not exist`

**Solution:**
```bash
# Install Composer dependencies first
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs

# Then start containers
docker-compose up -d
```

### View/File Issues

**Problem:** `View [auth.login] not found`

**Solution:**
```bash
# Check if view files exist
ls -la resources/views/auth/

# Make sure files have .blade.php extension
# Fix if needed:
cd resources/views/auth
mv login login.blade.php
mv register register.blade.php

# Clear cache
docker-compose exec laravel.test php artisan view:clear
```

**Problem:** `Target class [App\Http\Controllers\AuthController] does not exist`

**Solution:**
```bash
# Check if controller files have .php extension
ls -la app/Http/Controllers/

# Fix if needed:
cd app/Http/Controllers
mv AuthController AuthController.php
mv AccountController AccountController.php

# Regenerate autoload
docker-compose exec laravel.test composer dump-autoload
docker-compose restart
```

### Permission Issues

**Problem:** Permission denied when writing to storage

**Solution:**
```bash
# Fix storage permissions
docker-compose exec laravel.test chmod -R 777 storage bootstrap/cache
```

---

## Testing the Application

### Manual Testing Steps

1. **Register a new account**
   - Go to http://localhost:8000/register
   - Fill in: First Name, Last Name, Contact Number, Email, Password
   - Note: Address is NOT required during registration
   - Submit and you'll be logged in automatically

2. **Login**
   - Go to http://localhost:8000/login
   - Enter your email and password
   - You'll be redirected to the dashboard

3. **Edit Account**
   - Click "Edit Account" button on dashboard
   - Update your personal information
   - Add/update your address (optional)
   - Change your password if needed
   - Submit to save changes

4. **Logout**
   - Click "Logout" button
   - You'll be redirected to login page

---





---

**Last Updated:** October 2025  
**Laravel Version:** 12.x  
**PHP Version:** 8.4
