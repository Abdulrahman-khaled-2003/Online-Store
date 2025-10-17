<?php

namespace App\Http\Controllers;

require base_path("./Core/Session.php");
require "Controller.php";

class HomeController extends Controller
{

    public function index()
    {
        $this->render("home", [
            "categories" => $this->getCategoriesWithProducts()
        ]);
    }


    private function getCategoriesWithProducts()
    {
        $categories = db()->fetchAll("SELECT categoryName , id FROM categories");

        foreach ($categories as &$category) {
            $categoryId = $category['id'];

            $products = db()->fetchAll(
                "SELECT * FROM products WHERE category_id = ?",
                [$categoryId]
            );
            
            $category['products'] = $products;
        }
        unset($category);
        return $categories;
    }
}
