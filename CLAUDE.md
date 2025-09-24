# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a PHP-based Online Store application using a custom MVC architecture without a framework. The application displays product categories and products with a simple routing system.

## Architecture

### Core Components

- **Entry Point**: `Public/index.php` - All requests are routed through this file
- **Router**: `src/Core/Router.php` - Custom routing system that maps URLs to controller methods using regex pattern matching
- **Database**: `src/Core/Database.php` - PDO wrapper with custom exception handling
- **Controllers**: Located in `src/Http/Controllers/` - Handle request logic
- **Views**: Located in `src/Views/` - PHTML templates for rendering HTML

### Routing Pattern

Routes are defined in `src/config/routes.php` using the format:
```php
$router->get("/path/{parameter}", "ControllerName::methodName");
```

Controllers must be in namespace `App\Http\Controllers` and located in `src/Http/Controllers/`.

### Database Configuration

Database settings are configured in `src/config/database.php` with MySQL as the default connection. The Database class uses PDO with:
- Custom exception handling via `QueryException`
- Error logging to `logs/error.log`
- Prepared statements for security

### Key Helper Functions (src/Core/Function.php)

- `base_path($path)`: Returns absolute path relative to src directory
- `view($path, $attributes)`: Renders a view template from src/Views/Template/
- `abort($code, $message)`: Handles HTTP error responses
- `dd($value)`: Debug function for var_dump and die

## Development Commands

### Running the Application

Since this is a vanilla PHP application, use PHP's built-in server:
```bash
php -S localhost:8000 -t Public/
```

Access the application at `http://localhost:8000`

## Current Features

- **Home Page** (`/`): Displays all product categories
- **Category Page** (`/category/{id}/products`): Shows products for a specific category
- **Error Handling**: Custom 404 and 500 error pages

## Data Structure

Currently, product and category data is hardcoded in the controllers. Both `HomeController` and `CategoryController` have duplicate `getCategories()` methods returning static arrays with:
- Categories: id, CategoryName, Products array
- Products: ProductName, ProductImage, ProductPrice

## Error Handling

The application has a custom exception system:
- `QueryException`: Database-related errors (extends RuntimeException)
- `RecordNotFoundException`: For missing data records
- Error logging to `logs/error.log` for database exceptions
- Generic error messages shown to users for security

## Adding New Routes

1. Define route in `src/config/routes.php`
2. Create controller in `src/Http/Controllers/`
3. Ensure controller namespace is `App\Http\Controllers`
4. Create view template in `src/Views/Template/`