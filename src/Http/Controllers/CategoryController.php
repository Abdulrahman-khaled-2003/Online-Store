<?php

namespace App\Http\Controllers;

use App\Core\Exception\RecordNotFoundException;
use App\Core\Session;
use App\Http\Validation\CategoryValidation;

require base_path("./Core/Exceptions/RecordNotFoundException.php");
require base_path("./Core/Session.php");
require base_path("Http/Validation/CategoryValidation.php");
require "Controller.php";

class CategoryController extends Controller
{
    public function show(int $id)
    {
            view("Category/show", [
                "products" => $this->getProducts($id),
                "category" => $this->getCategory($id),
                "categories" => $this->getCategories()
            ]);
            die();
    }

    public function index()
    {
        $this->render("Categories/index", [
            "categories" => $this->getCategories(),
        ]);
    }

    public function create()
    {
        $this->render("Categories/create", [
            "categories" => $this->getCategories(),
        ]);
    }

    public function store(array $attributes)
    {
        $validated = new CategoryValidation($attributes, $_FILES);

        if (!empty($validated->errors)) {
            $this->render("Categories/create", [
                "categories" => $this->getCategories(),
                "errors" => $validated->errors
            ]);
        }
        $extenstion = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

        db()->execute("INSERT INTO categories (categoryName , categoryDescription , categoryImage) 
        VALUE (?, ?, ?)", [
            $attributes['category-name'],
            $attributes["category-desc"],
            $attributes['category-name'] . "." . $extenstion
        ]);
        Session::flash("Success-Message", $attributes['category-name'] . " Added Successfully");
        redirect("/categories");
    }

    public function edit(int $id)
    {
        $this->render("Categories/edit", [
            "category" => $this->getCategory($id),
            "categories" => $this->getCategories()
        ]);
    }

    public function update(array $attributes)
    {
        $validated = new CategoryValidation($attributes, $_FILES);

        if (! empty($validated->errors)) {
            $this->render("Categories/edit", [
                "categories" => $this->getCategories(),
                "category" => $this->getCategory($attributes['id']),
                "error" => $validated->errors
            ]);
        }

        $oldImage = $this->getCategoryImage($attributes['id']);
        $extension = checkImage($_FILES, $oldImage['categoryImage']);

        db()->execute("UPDATE categories set categoryName = ? , categoryDescription = ? , categoryImage = ? where id = ?", [
            $attributes['category-name'],
            $attributes['category-desc'],
            $attributes['category-image'],
            $attributes['id']
        ]);
        Session::flash("Success-Message", $attributes['category-name'] . " Updated Successfully");
        redirect("/categories");
    }

    public function destroy(array $attributes)
    {
        if (!empty($this->getProducts($attributes['id']))) {
            Session::flash("Success-Message", "You Can't Delete This Category Because Contain Some Products!");
        } else {
            db()->execute("DELETE From categories where id = ?", [$attributes['id']]);
            Session::flash("Success-Message", " Deleted Successfully");
        }

        redirect("/categories");
    }

    private function getCategories()
    {
        return db()->fetchAll("SELECT * FROM categories");
    }

    private function getCategory(int $id)
    {
        $category = db()->fetch("SELECT * FROM categories where id = ? LIMIT 1 ", [$id]) ?? null;

        if ($category === false) {
            throw new RecordNotFoundException("Category with ID {$id} not found!");
        }
        return $category;
    }

    private function getProducts(int $id)
    {
        return db()->fetchAll("SELECT * FROM products where category_id = ?", [$id]);
    }

    private function getCategoryImage(int $id)
    {
        return db()->fetch("SELECT categoryImage FROM categories where id = ?", [$id]);
    }
}
