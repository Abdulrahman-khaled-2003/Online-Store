# Online Store

A simple **E-commerce** application built with **PHP** and **MySQL** using a custom **MVC architecture**. This project was created as a learning experience to understand how modern web applications work behind the scenes without relying on frameworks.

The application demonstrates core backend concepts such as routing, controllers, views, database interaction, CRUD operations, database migrations, console commands, and object-oriented programming principles.

---

## Features

* Product Management (CRUD)
* Category Management
* Product Colors
* Product Sizes
* Database Relationships
* Custom MVC Architecture
* Custom Router
* PDO Database Connection
* PSR-4 Autoloading
* Object-Oriented PHP
* Database Migration System
* Symfony Console Commands
* Console Error Handling

---

## Technologies Used

* PHP 8+
* MySQL
* HTML5
* Tailwind CSS
* MVC Architecture
* PDO
* Composer
* Composer Autoloading (PSR-4)
* Symfony Console

---

## Requirements

Before running the project, make sure the following software is installed:

* PHP **8.0** or higher
* MySQL **5.7+** or MariaDB **10.3+**
* Git
* Composer
* PHP Built-in Web Server

Optional:

* phpMyAdmin
* MySQL Workbench
* Laragon / XAMPP

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/Abdelrhman-2003/Online-Store.git

cd Online-Store
```

---

### 2. Install Composer Dependencies

Install the project dependencies and generate the Composer autoloader:

```bash
composer install
```

This installs the required Composer packages, including **Symfony Console**, and generates the Composer autoloader.

---

### 3. Create the Database

Create a new database named:

```sql
CREATE DATABASE online_store;
```

---

### 4. Configure the Database

Open:

```text
src/config/database.php
```

Update your database credentials.

Example:

```php
return [
    "default" => "mysql",

    "connections" => [
        "mysql" => [

            "dbname"   => "online_store",
            "host"     => "localhost",
            "port"     => 3306,
            "username" => "root",
            "password" => ""

        ]
    ]
];
```

---

### 5. Verify PHP Installation

Run:

```bash
php -v
```

You should see PHP **8.0** or later.

---

### 6. Run Database Migrations

After configuring the database, run:

```bash
php console migrate:run
```

This will execute all pending migrations and create the required database tables.

For more information about the available migration commands, see the [Database Migrations](#database-migrations) section.

---

### 7. Start the Development Server

```bash
php -S localhost:8000 -t Public/
```

Open your browser:

```text
http://localhost:8000
```

If everything is configured correctly, the application should load successfully.

---

# Database Migrations

The project includes a custom **database migration system** powered by **Symfony Console**.

Migrations provide a structured and version-controlled way to manage database schema changes without manually executing SQL statements every time the database structure changes.

All migration files are stored inside:

```text
src/Database/Migrations/
```

Each migration contains two methods:

* `up()` — applies the database changes.
* `down()` — reverses the database changes.

---

## Console Application

The migration system is exposed through a dedicated console entry point:

```text
console
```

The console application is built using **Symfony Console** and provides a structured command-line interface for managing migrations.

General syntax:

```bash
php console <command> [arguments]
```

To see all available commands:

```bash
php console list
```

---

## Available Migration Commands

### 1. Create a New Migration

Create a new migration using:

```bash
php console migrate:make <migration_name>
```

Example:

```bash
php console migrate:make create_colors_table
```

This generates a timestamped migration file inside:

```text
src/Database/Migrations/
```

The generated migration contains empty `up()` and `down()` methods ready to be implemented.

Example structure:

```php
<?php

namespace App\Database\Migrations;

use App\Core\Migration;

class CreateColorsTable extends Migration
{
    public function up(): void
    {
        // Create table
    }

    public function down(): void
    {
        // Drop table
    }
}
```

The migration name must start with a lowercase letter or underscore, followed by lowercase letters, numbers, or underscores.

Valid examples:

```text
create_colors_table
create_products_table
add_price_to_products
add_user_id_to_orders
```

---

### 2. Run Migrations

Run all pending migrations:

```bash
php console migrate:run
```

The migration runner:

1. Finds migration files.
2. Determines which migrations have already been executed.
3. Executes only pending migrations.
4. Records executed migrations in the `migrations` table.
5. Groups migrations executed during the same run into a batch.

Example:

```bash
php console migrate:run
```

If migrations are successfully executed, the console displays formatted success messages.

If no pending migrations exist, the console displays a warning.

---

### 3. Rollback Migrations

Rollback the latest migration batch:

```bash
php console migrate:rollback
```

The rollback process:

1. Finds the latest migration batch.
2. Executes the `down()` method of each migration in that batch.
3. Removes the executed migrations from the `migrations` table.

Example:

```bash
php console migrate:rollback
```

This allows the latest group of database changes to be reversed.

---

## Migration Batches

The migration system uses **batch numbers** to group migrations.

For example, running:

```bash
php console migrate:run
```

may execute:

```text
CreateCategoriesTable
CreateProductsTable
CreateColorsTable
```

All migrations executed during that run belong to the same batch.

The database may contain:

| migration               | batch |
| ----------------------- | ----- |
| create_categories_table | 1     |
| create_products_table   | 1     |
| create_colors_table     | 1     |

If another migration is created and executed later:

```text
add_price_to_products
```

it may belong to batch `2`.

Therefore:

```bash
php console migrate:rollback
```

rolls back the latest batch rather than randomly selecting a migration.

---

## Console Error Handling

Console commands use Symfony Console's output system to provide formatted feedback.

For example, successful operations are displayed using formatted success messages, while warnings and errors are displayed using appropriate console styles.

The application also handles runtime exceptions during console execution.

For example, if the database connection fails:

```text
[ERROR]
SQLSTATE[HY000] [1045] Access denied for user...
```

The command exits with a failure status instead of silently continuing after an exception.

The console application also uses appropriate exit codes:

* `0` — successful execution
* non-zero — command failed

This makes the console suitable for both manual usage and future automation or scripts.

---

## Symfony Console Architecture

The console application is separated into commands, while the migration logic remains inside the project's core classes.

The basic flow is:

```text
php console migrate:run
        │
        ▼
Console Application
        │
        ▼
MigrationRunCommand
        │
        ▼
MigrationRunner
        │
        ▼
Database
```

For creating migrations:

```text
php console migrate:make create_users_table
        │
        ▼
Console Application
        │
        ▼
MakeMigrationCommand
        │
        ▼
MigrationCreator
        │
        ▼
Migration File
```

Symfony Console is responsible for the **command-line interface**, including:

* Command registration
* Arguments
* Input handling
* Console output
* Formatting
* Exit codes
* Command execution lifecycle

The project's core classes remain responsible for the actual migration logic.

---

## Migration Files Location

All migration files live inside:

```text
src/Database/Migrations/
```

Example:

```text
src/Database/Migrations/
├── 2026_09_01_120000_create_categories_table.php
├── 2026_09_01_120500_create_products_table.php
├── 2026_09_01_121000_create_colors_table.php
└── 2026_09_01_121500_create_sizes_table.php
```

Each migration contains:

```php
public function up(): void
```

and:

```php
public function down(): void
```

The `up()` method applies the migration, while `down()` reverses it.

---

## Quick Migration Workflow

After cloning the project:

```bash
cd Online-Store
```

Install dependencies:

```bash
composer install
```

Configure the database:

```text
src/config/database.php
```

Run all migrations:

```bash
php console migrate:run
```

Create a new migration when needed:

```bash
php console migrate:make create_orders_table
```

After implementing the migration, run:

```bash
php console migrate:run
```

To undo the latest migration batch:

```bash
php console migrate:rollback
```

To see all available commands:

```bash
php console list
```

---

## Database Structure

The application uses a relational MySQL database to organize products, categories, colors, and sizes.

### Categories

Stores all product categories.

| Column              | Description          |
| ------------------- | -------------------- |
| id                  | Primary key          |
| categoryName        | Category name        |
| categoryDescription | Category description |

---

### Products

Stores the application's products.

| Column             | Description                |
| ------------------ | -------------------------- |
| id                 | Primary key                |
| productName        | Product name               |
| productDescription | Product description        |
| price              | Product price              |
| category_id        | References `categories.id` |

---

### Colors

Stores the available colors.

| Column    | Description |
| --------- | ----------- |
| id        | Primary key |
| colorName | Color name  |

---

### Sizes

Stores the available product sizes.

| Column   | Description |
| -------- | ----------- |
| id       | Primary key |
| sizeName | Size name   |

---

### Product_Color

A pivot table that represents the many-to-many relationship between products and colors.

---

### Product_Size

A pivot table that represents the many-to-many relationship between products and sizes.

---

## Database Relationships

* One **Category** can contain many **Products**.
* One **Product** belongs to one **Category**.
* One **Product** can have multiple **Colors**.
* One **Color** can belong to multiple **Products**.
* One **Product** can have multiple **Sizes**.
* One **Size** can belong to multiple **Products**.

---

## Project Structure

```text
Online-Store/
│
├── Public/
│   ├── assets/
│   │   └── images/
│   │       ├── Categories/       # Category images
│   │       └── Products/         # Product images
│   └── index.php                 # Application entry point (Front Controller)
│
├── src/
│   ├── Core/                     # Core framework classes
│   │   ├── Auth.php
│   │   ├── Database.php
│   │   ├── Function.php
│   │   ├── Hash.php
│   │   ├── Migration.php
│   │   ├── MigrationCreator.php
│   │   ├── MigrationRunner.php
│   │   ├── Model.php
│   │   ├── Router.php
│   │   ├── Session.php
│   │   ├── Validation.php
│   │   └── Exceptions/           # Custom exception classes
│   │
│   ├── Console/
│   │   └── Commands/             # Symfony Console commands
│   │       ├── MakeMigrationCommand.php
│   │       ├── MigrationRunCommand.php
│   │       └── MigrationRollbackCommand.php
│   │
│   ├── Http/
│   │   ├── Controllers/          # Handle incoming HTTP requests
│   │   │   ├── AuthController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── Controller.php
│   │   │   ├── HomeController.php
│   │   │   └── ProductController.php
│   │   └── Validation/           # Request validation classes and rules
│   │       ├── CategoryValidation.php
│   │       ├── ImageValidation.php
│   │       ├── LoginValidation.php
│   │       ├── ProductValidation.php
│   │       └── RegisterValidation.php
│   │
│   ├── Models/                   # Eloquent-style models
│   │   ├── Category.php
│   │   ├── Color.php
│   │   ├── Product.php
│   │   ├── ProductColor.php
│   │   ├── ProductSize.php
│   │   ├── Size.php
│   │   └── User.php
│   │
│   ├── Database/
│   │   └── Migrations/           # Database migration files
│   │
│   ├── Views/
│   │   ├── Partials/             # Reusable view components
│   │   └── Template/              # Page templates
│   │       ├── Auth/
│   │       ├── Categories/
│   │       ├── Category/
│   │       ├── Products/
│   │       ├── home.phtml
│   │       └── statusCode.phtml
│   │
│   └── config/
│       ├── database.php          # Database configuration
│       └── routes.php            # Route definitions
│
├── logs/
│   └── error.log                 # Application error log
│
├── console                       # Symfony Console entry point
├── composer.json
├── composer.lock
└── README.md                     # Project documentation
```

---

## Running the Application

After completing the installation steps:

### 1. Start MySQL

Make sure your MySQL server is running.

### 2. Run Migrations

```bash
php console migrate:run
```

### 3. Start the PHP Development Server

```bash
php -S localhost:8000 -t Public/
```

### 4. Open the Application

```text
http://localhost:8000
```

---

## Quick Start

Every time you want to continue working on the project:

```bash
cd Online-Store

composer install

php console migrate:run

php -S localhost:8000 -t Public/
```

Then open:

```text
http://localhost:8000
```

---

## Contributing

Contributions are welcome.

If you'd like to improve the project:

1. Fork the repository.
2. Create a new feature branch.
3. Commit your changes.
4. Push the branch.
5. Open a Pull Request.

Please keep the coding style consistent with the existing project structure.

---

## License

This project was created for learning purposes and is open for educational use.
