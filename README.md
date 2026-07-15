# Online Store

A simple **E-commerce** application built with **PHP** and **MySQL** using a custom **MVC architecture**. This project was created as a learning experience to understand how modern web applications work behind the scenes without relying on frameworks.

The application demonstrates core backend concepts such as routing, controllers, views, database interaction, and CRUD operations while following clean code practices and object-oriented programming principles.

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

---

## Technologies Used

* PHP 8+
* MySQL
* HTML5
* CSS3
* MVC Architecture
* PDO
* Composer Autoloading (PSR-4)

---

## Requirements

Before running the project, make sure the following software is installed:

* PHP **8.0** or higher
* MySQL **5.7+** or MariaDB **10.3+**
* Git
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

### 2. Create the Database

Create a new database named:

```sql
CREATE DATABASE online_store;
```

---

### 3. Import the SQL File

Import the provided SQL file into the database.

For example:

```bash
mysql -u root -p online_store < online_store.sql
```

Or import it using **phpMyAdmin**.

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

### 6. Start the Development Server

```bash
php -S localhost:8000 -t Public/
```

Open your browser:

```
http://localhost:8000
```

If everything is configured correctly, the application should load successfully.

---

## Database Structure

The application uses a relational MySQL database to organize products, categories, colors, and sizes.

### Categories

Stores all product categories.

| Column              | Description           |
| ------------------- | --------------------- |
| id                  | Primary key           |
| categoryName        | Category name         |
| categoryDescription | Category description  |
| created_at          | Creation timestamp    |
| updated_at          | Last update timestamp |

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
| created_at         | Creation timestamp         |
| updated_at         | Last update timestamp      |

---

### Colors

Stores the available colors.

| Column     | Description           |
| ---------- | --------------------- |
| id         | Primary key           |
| colorName  | Color name            |
| created_at | Creation timestamp    |
| updated_at | Last update timestamp |

---

### Sizes

Stores the available product sizes.

| Column     | Description           |
| ---------- | --------------------- |
| id         | Primary key           |
| sizeName   | Size name             |
| created_at | Creation timestamp    |
| updated_at | Last update timestamp |

---

### Product_Color

A pivot table that represents the many-to-many relationship between products and colors.

---

### Product_Size

A pivot table that represents the many-to-many relationship between products and sizes.

---

### Database Relationships

* One **Category** can contain many **Products**.
* One **Product** belongs to one **Category**.
* One **Product** can have multiple **Colors**.
* One **Color** can belong to multiple **Products**.
* One **Product** can have multiple **Sizes**.
* One **Size** can belong to multiple **Products**.

---

## Project Structure

Online-Store/

│
├── Public/                         # Publicly accessible files
│   ├── assets/                     # CSS, JavaScript, Images, Fonts
│   └── index.php                   # Application entry point (Front Controller)
│
├── src/
│   ├── Core/                       # Core framework classes
│   │
│   ├── Http/
│   │   ├── Controllers/            # Handle incoming HTTP requests
│   │   └── Validation/             # Validation classes and rules
│   │
│   ├── Views/
│   │   ├── Partials/               # Reusable view components
│   │   └── Templates/              # Page templates
│   │
│   └── config/
│       ├── database.php            # Database configuration
│       └── routes.php              # Route definitions
│
└── README.md                       # Project documentation
```


---

## Running the Application

After completing the installation steps:

1. Start your MySQL server.
2. Start the PHP development server.

```bash
php -S localhost:8000 -t Public/
```

Open your browser and visit:

```
http://localhost:8000
```

The application should now be running.

---

## Quick Start

Every time you want to continue working on the project:

```bash
cd Online-Store

php -S localhost:8000 -t Public/
```

Then open:

```
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