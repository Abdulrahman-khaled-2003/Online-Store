<?php

namespace App\Http\Controllers;

use App\Core\Exception\RecordNotFoundException;
use App\Core\Session;
use App\Http\Validation\FormValidation;

require base_path("./Core/Exceptions/RecordNotFoundException.php");
require base_path("./Core/Session.php");
require base_path("Http/Validation/FormValidation.php");
require "Controller.php";

class ProductController extends Controller
{
    public function index()
    {
        view("Products/index", [
            "products" => $this->getProducts(),
            "categories" => $this->getCategories()
        ]);
    }


    private function createProduct() {}

    private function editProduct(int $id) {}

    private function deleteProduct(int $id) {}

    private function getProduct(int $id) {}

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
}
