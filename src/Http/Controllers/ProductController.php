<?php

namespace App\Http\Controllers;

use App\Core\Exception\RecordNotFoundException;
use App\Http\Validation\ProductValidation;

require base_path("./Core/Exceptions/RecordNotFoundException.php");
require base_path("./Core/Session.php");
require base_path("Http/Validation/ProductValidation.php");
require "Controller.php";

class ProductController extends Controller
{
    protected $validated;

    public function index(): void
    {
        $this->render("Products/index", [
            "products" => $this->getProducts(),
            "categories" => $this->getCategories()
        ]);
    }

    public function show(int $id): void
    {
        $this->render("Products/show", [
            "categories" => $this->getCategories(),
            "product" => $this->getProduct($id),
            "productColor" => $this->getProductColors($id),
            "productSize" => $this->getProductSizes($id)
        ]);
    }

    public function create(int $id): void
    {
        $this->render("Products/create", [
            "errors" => $this->validated->errors ?? null,
            "category" => $this->getCategoryNameAndId($id),
            "categories" => $this->getCategories(),
            "colors" => $this->getColors(),
            "sizes" => $this->getSizes()
        ]);
    }

    public function store(array $attributes): void
    {
        $this->validated = new ProductValidation($attributes, $_FILES);
        if (! empty($this->validated->errors)) {
            $this->create($attributes['id']);
        }
        $extenstion = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $this->createProduct($attributes, $extenstion);
        redirect("/products");
    }

    public function indexCards(): void
    {
        $this->render("Products/indexCards", [
            "categories" => $this->getCategories()
        ]);
    }

    // Database helper function Query

    private function editProduct(int $id) {}

    private function deleteProduct(int $id) {}

    private function getCategories(): array
    {
        return db()->fetchAll("SELECT * FROM categories");
    }

    private function getCategoryNameAndId(int $id): array
    {
        $categoryNameAndId = db()->fetch("SELECT categoryName , id FROM categories where id = ? LIMIT 1", [$id]);
        if ($categoryNameAndId === false) {
            throw new RecordNotFoundException("Category with ID {$id} not found!");
        }
        return $categoryNameAndId;
    }

    private function getColorID(string $colorName): array
    {
        return db()->fetch("SELECT id from colors where colorName = ? LIMIT 1", ["$colorName"]);
    }

    private function getSizeID(string $sizeName): array
    {
        return db()->fetch("SELECT id FROM sizes where sizeName = ? LIMIT 1", ["$sizeName"]);
    }

    private function getColors(): array
    {
        return db()->fetchAll("SELECT * FROM colors");
    }

    private function getSizes(): array
    {
        return db()->fetchAll("SELECT * FROM sizes");
    }

    private function getProductColors(int $id): array
    {
        return db()->fetchAll(
            "SELECT c.colorName FROM product_color pc JOIN colors c ON pc.color_id = c.id WHERE pc.product_id = ?",
            [$id]
        );
    }
    private function getProductSizes(int $id): array
    {
        return db()->fetchAll(
            "SELECT s.sizeName FROM product_size pc JOIN sizes s ON pc.size_id = s.id WHERE pc.product_id = ?",
            [$id]
        );
    }

    private function getProducts(): array
    {
        return db()->fetchAll("SELECT p.id, p.productName, p.productDescription, p.productPrice, p.productImage, c.categoryName 
        AS categoryName
        FROM products p
        JOIN categories c ON p.category_id = c.id ");
    }

    private function getProduct(int $id): array
    {
        $product = db()->fetch("SELECT p.id, p.productName, p.productDescription, p.productPrice, p.productImage, c.categoryName FROM products p
        JOIN categories c ON p.category_id = c.id  WHERE p.id = ?", [$id]);
        if ($product === false) {
            throw new RecordNotFoundException("Product with ID {$id} not found!");
        }
        return $product;
    }

    private function createProduct(array $attributes, string $extension)
    {
        db()->execute("INSERT INTO products(`productName`, `productImage`, `productDescription`, `productPrice`, `category_id`) 
        VALUES (?, ?, ?, ?, ? )", [
            $attributes['product_name'],
            $attributes['product_name'] . "." . $extension,
            $attributes['description'],
            $attributes['price'],
            $attributes['id']
        ]);

        $lastID = db()->getLastId();

        $colors = $attributes['colors'] ?? [];
        $sizes = $attributes['sizes'] ?? [];

        foreach ($colors as $color) {
            $colorID = $this->getColorID($color);
            db()->execute("INSERT INTO product_color (`product_id`, `color_id`) VALUES (?, ?)", [
                $lastID,
                $colorID['id']
            ]);
        }
        foreach ($sizes as $size) {
            $sizeID = $this->getSizeID($size);
            db()->execute("INSERT INTO product_size (`product_id`, `size_id`) VALUES (?, ?)", [
                $lastID,
                $sizeID['id']
            ]);
        }
    }
}
