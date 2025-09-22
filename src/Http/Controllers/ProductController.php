<?php

namespace App\Http\Controllers;

use App\Core\Exception\RecordNotFoundException;
use App\Core\Session;
use App\Core\Validation;
use App\Http\Validation\FormValidation;
use App\Http\Validation\ProductValidation;
use Exception;

require base_path("./Core/Exceptions/RecordNotFoundException.php");
require base_path("./Core/Session.php");
require base_path("Http/Validation/ProductValidation.php");
require "Controller.php";

class ProductController extends Controller
{
    protected $uri;
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
            "product" => $this->getProduct($id)
        ]);
    }

    public function create(int $id): void
    {
        $categoryName = db()->fetch("SELECT categoryName , id FROM categories where id = ? LIMIT 1", [$id]);
        if ($categoryName === false) {
            throw new RecordNotFoundException("Category with ID {$id} not found!");
        }

        $this->render("Products/create", [
            "errors" => $this->validated->errors ?? null,
            "category" => $categoryName,
            "categories" => $this->getCategories(),
            "colors" => $this->getColors(),
            "sizes" => $this->getSizes()
        ]);
        exit();
    }

    public function store(array $attributes): void
    {
        $this->validated = new ProductValidation($attributes, $_FILES);
        if (! empty($this->validated->errors)) {
            $this->create($attributes['id']);
        }
        redirect("/products");
    }

    public function indexCards(): void
    {
        $this->render("Products/indexCards", [
            "categories" => $this->getCategories()
        ]);
    }

    private function createProduct(array $attributes) {}

    private function editProduct(int $id) {}

    private function deleteProduct(int $id) {}

    private function getProduct(int $id): array
    {
        $product = db()->fetch("SELECT p.id, p.productName, p.productDescription, p.productPrice, p.productImage, c.categoryName FROM products p
    JOIN categories c ON p.category_id = c.id  WHERE p.id = ?", [$id]);

        if ($product === false) {
            throw new RecordNotFoundException("Category with ID {$id} not found!");
        }
        $product = [
            "product" => $product,
            "productColor" => $this->getProductColor($product['id'])
        ];

        return $product;
    }

    private function getProductColor(int $id): array
    {
        return db()->fetchAll("SELECT * FROM product_color where product_id = ?", [$id]);
    }

    private function getProducts(): array
    {
        return db()->fetchAll("SELECT p.id, p.productName, p.productDescription, p.productPrice, p.productImage, c.categoryName 
        AS categoryName
        FROM products p
        JOIN categories c ON p.category_id = c.id ");
    }

    private function getCategories(): array
    {
        return db()->fetchAll("SELECT * FROM categories");
    }

    private function getColors(): array
    {
        return db()->fetchAll("SELECT * FROM colors");
    }

    private function getSizes(): array
    {
        return db()->fetchAll("SELECT * FROM sizes");
    }
}
