<?php

$router->get("/", "HomeController::index");
$router->get("/category/{id}/products", "CategoryController::show");

// Categories
$router->get("/categories", "CategoryController::index");
$router->get("/categories/create", "CategoryController::create");
$router->post("/categories/store", "CategoryController::store");
$router->get("/categories/edit/{id}", "CategoryController::edit");
$router->put("/categories/update", "CategoryController::update");
$router->delete("/categories/destroy", "CategoryController::destroy");

//Products
$router->get("/products", "ProductController::index");
$router->get("/products/show/{id}", "ProductController::show");
$router->get("/products/cards", "ProductController::indexCards");
$router->get("/products/create/{id}", "ProductController::create");
$router->post("/products/store" , "ProductController::store");
$router->get("/products/edit/{id}" , "ProductController::edit");
$router->put("/products/update" , "ProductController::update");
$router->delete("/products/destroy" , "ProductController::destroy");
