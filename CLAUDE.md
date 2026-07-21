# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a PHP-based Online Store application built on a **custom, Laravel-inspired MVC architecture crafted from scratch — no framework**. It displays product categories and products, and provides full CRUD management for categories backed by a MySQL database.

### Learning Context

This is a **learning project** for a fresh-graduate student preparing to enter the web development industry. The point is not just a working store, but understanding *how a framework like Laravel works under the hood* by rebuilding its pieces by hand:

- The custom **Router** mirrors Laravel's `Route::get()/post()/put()/delete()` facade.
- The **`db()` helper**, `view()`, `redirect()`, and `abort()` echo Laravel's global helpers.
- **Method spoofing** via a hidden `_method` field is exactly how Laravel handles PUT/DELETE from HTML forms (Blade's `@method('PUT')`).
- **Flash messages** (`Session::flash()`) mirror Laravel's `session()->flash()`.
- The base **`Controller`** and per-form **validation** classes parallel Laravel's `Controller` and Form Requests.

When explaining or reviewing code, it helps to point out these parallels — they connect what's being built here to the industry-standard framework the student is heading toward.

### Communication Style (Issues & PR Comments)

Because this is a learning project, **GitHub issues and PR review comments must be written to teach**, not just to instruct:

- Use **simple, plain language**; avoid unexplained jargon, and when a technical term is necessary, briefly define it.
- **Explain the "why"** behind a suggestion, not only the "what" — the reasoning is the lesson.
- Prefer small, concrete examples over abstract advice.
- Link to **trusted resources** when they help (e.g. the [PHP Manual](https://www.php.net/manual/en/), [Laravel docs](https://laravel.com/docs), [MDN Web Docs](https://developer.mozilla.org/), [OWASP](https://owasp.org/)), so the student can read further.
- Be encouraging and constructive — frame feedback as a learning opportunity, not a mistake.

## Architecture

### Core Components

- **Entry Point**: `Public/index.php` - Bootstraps sessions, loads helpers/router/routes, dispatches the request, and catches exceptions. Closes the DB connection in a `finally` block.
- **Router**: `src/Core/Router.php` - Custom routing that maps URLs to controller methods using regex pattern matching. Supports GET, POST, PUT, PATCH, DELETE. Throws `FileNotFoundException` when a controller file is missing.
- **Database**: `src/Core/Database.php` - PDO wrapper (`fetch`, `fetchAll`, `execute`) with prepared statements and `QueryException` handling. Accessed via the `db()` helper singleton.
- **Controllers**: Located in `src/Http/Controllers/` - Handle request logic. `Controller.php` is an abstract base providing a `render()` helper.
- **Validation**: `src/Core/Validation.php` (reusable rules) and `src/Http/Validation/FormValidation.php` (per-form rule set).
- **Session**: `src/Core/Session.php` - Static wrapper for `$_SESSION` with flash-message support.
- **Views**: Located in `src/Views/` - PHTML templates (`Template/`) and reusable `Partials/`.

### Request Lifecycle

1. `Public/index.php` starts the session and computes `$uri` and `$method`.
2. `$method` is resolved from `$_POST["_method"]` if present (method spoofing for PUT/DELETE from HTML forms), otherwise `$_SERVER["REQUEST_METHOD"]`.
3. The router matches the route, `require`s and instantiates the controller, and calls the method. Route parameters plus `$_POST` are passed as arguments (POST body arrives as the final `array $attributes` argument).

### Routing Pattern

Routes are defined in `src/config/routes.php`:
```php
$router->get("/path/{parameter}", "ControllerName::methodName");
$router->post("/path", "ControllerName::methodName");
$router->put("/path", "ControllerName::methodName");
$router->delete("/path", "ControllerName::methodName");
```

Controllers must be in namespace `App\Http\Controllers` and located in `src/Http/Controllers/`.

Since browsers only send GET/POST, PUT and DELETE routes are triggered from forms by including a hidden `_method` field (e.g. `<input type="hidden" name="_method" value="PUT">`).

### Database Configuration

Database settings live in `src/config/database.php` (MySQL default; database `online_store`, user `root`, no password by default). The Database class uses PDO with:
- `PDO::FETCH_ASSOC` default fetch mode and `PDO::ERRMODE_EXCEPTION`
- Custom exception handling via `QueryException`
- Prepared statements for security

Expected tables: `categories` (`id`, `categoryName`, `description`, `image`) and `products` (`id`, `category_id`, ...).

### Key Helper Functions (src/Core/Function.php)

- `db()`: Returns a shared `Database` singleton built from `config/database.php`.
- `base_path($path)`: Returns absolute path relative to the `src` directory.
- `view($path, $attributes)`: Renders a view template from `src/Views/Template/`.
- `redirect($path, $code)`: Sends a `Location` header and exits.
- `abort($code, $message)`: Renders the `statusCode` error view with an HTTP status.
- `errorLog($error, $file, $line)`: Appends a formatted entry to `logs/error.log`.
- `serverError($error, $file, $line)`: Logs the error and aborts with a generic 500 message.
- `stringToArray($separator, $string)`: Thin wrapper around `explode()` (used to split `"Class::method"`).
- `dd($value)`: Debug helper for `var_dump` and die.

## Development Commands

### Running the Application

Since this is a vanilla PHP application, use PHP's built-in server:
```bash
php -S localhost:8000 -t Public/
```

Access the application at `http://localhost:8000`. A MySQL database named `online_store` must exist and be reachable with the credentials in `src/config/database.php`.

## Current Features

- **Home Page** (`/`): Displays all product categories.
- **Category Products** (`/category/{id}/products`): Shows products for a specific category.
- **Category CRUD**:
  - `GET /categories` - List categories (`CategoryController::index`)
  - `GET /categories/create` - Show create form (`create`)
  - `POST /categories/store` - Persist a new category (`store`)
  - `GET /categories/edit/{id}` - Show edit form (`edit`)
  - `PUT /categories/update` - Update a category (`update`)
  - `DELETE /categories/destroy` - Delete a category, blocked if it still has products (`destroy`)
- **Flash Messages**: Success/error notices passed across redirects via `Session::flash()`.
- **Form Validation**: `FormValidation` checks category name/description length and image extension.
- **Error Handling**: Custom 404 and 500 error pages.

## Data Structure

Data handling is currently mixed:
- **`CategoryController`** reads from and writes to the MySQL database via `db()` (`categories` / `products` tables).
- **`HomeController`** still returns a hardcoded `getCategories()` array (categories with nested `Products`: `ProductName`, `ProductImage`, `ProductPrice`). This is legacy static data not yet migrated to the database.

## Error Handling

The application has a custom exception system:
- `QueryException`: Database-related errors (extends `RuntimeException`).
- `RecordNotFoundException`: For missing data records (caught in `index.php` and turned into a 404).
- `FileNotFoundException`: Thrown by the router when a controller file is missing.
- Errors are logged to `logs/error.log`; generic messages are shown to users for security.

## Conventions

- Files are loaded with explicit `require` statements (no Composer autoloader). New classes must be `require`d where used, typically via `base_path(...)`.
- Controllers extending the base `Controller` should render via `$this->render($view, $attributes)`, which renders the view, clears flash data, and exits.

## Adding New Routes

1. Define the route in `src/config/routes.php` (use the correct HTTP verb helper).
2. Create the controller in `src/Http/Controllers/` with namespace `App\Http\Controllers`.
3. `require` any dependencies the controller needs (exceptions, validation, base `Controller`).
4. Create the view template in `src/Views/Template/`.
5. For PUT/DELETE forms, include a hidden `_method` field.
