<?php

namespace App\Http\Controllers;

require base_path("./Core/Session.php");
require "Controller.php";

class HomeController extends Controller
{
    public function index()
    {
        $this->render("home", [
            "categories" => $this->getCategories(),
            "products" => $this->getProducts()
        ]);
    }

    private function getCategories()
    {
        return db()->fetchAll("SELECT * FROM categories");
    }

    private function getProducts()
    {
        $products = db()->fetchAll("SELECT * FROM products");
        $productsByCategorId = [];
        foreach ($products as $product) {
            $productsByCategorId[$product['category_id']][] = $product;
        }
        return $productsByCategorId;
    }
}