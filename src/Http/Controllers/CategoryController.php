<?php

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Session;
use App\Http\Validation\CategoryValidation;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show(int $id)
    {
        $this->render("Category/show", [
            "products" => Category::getProducts($id),
            "category" => Category::getCategory($id),
            "categories" => Category::getCategories()
        ]);
    }

    public function index()
    {
        $this->render("Categories/index", [
            "categories" => Category::getCategories(),
        ]);
    }

    public function create()
    {
        Auth::require();
        $this->render("Categories/create", [
            "categories" => Category::getCategories(),
        ]);
    }

    public function store(array $attributes)
    {
        Auth::require();
        $validated = new CategoryValidation($attributes, $_FILES);
        if (!empty($validated->errors)) {
            $this->render("Categories/create", [
                "categories" => Category::getCategories(),
                "errors" => $validated->errors
            ]);
        }
        $extenstion = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        Category::create($attributes, $extenstion);
        Session::flash("Success-Message", $attributes['category-name'] . " Added Successfully");
        redirect("/categories");
    }

    public function edit(int $id)
    {
        Auth::require();
        $this->render("Categories/edit", [
            "category" => Category::getCategory($id),
            "categories" => Category::getCategories()
        ]);
    }

    public function update(array $attributes)
    {
        Auth::require();
        $this->validated = new CategoryValidation($attributes, $_FILES);
        if (! empty($this->validated->errors)) {
            $this->render("Categories/edit", [
                "categories" => Category::getCategories(),
                "category" => Category::getCategory($attributes['id']),
                "error" => $this->validated->errors
            ]);
        }
        $oldImage = Category::getCategoryImage($attributes['id']);
        $extension = checkImage($_FILES, $oldImage['categoryImage']);
        Category::update($attributes, $extension);
        Session::flash("Success-Message", $attributes['category-name'] . " Updated Successfully");
        redirect("/categories");
    }

    public function destroy(array $attributes)
    {
        Auth::require();
        Category::destroy($attributes['id']);
        redirect("/categories");
    }
}
