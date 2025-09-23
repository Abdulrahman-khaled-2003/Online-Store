<?php

namespace App\Http\Validation;

use App\Core\Validation;

require base_path("Core/Validation.php");

class ProductValidation
{
    public $errors = [];
    public function __construct(array $attributes, array $image)
    {
        if (! Validation::textValidate($attributes['product_name'])) {
            $this->errors['productName'] = "Invalid Product Name!";
        }

        if (! Validation::textValidate($attributes['description'])) {
            $this->errors['productDescription'] = "Invalid Description!";
        }

        if (! Validation::checkNum($attributes["price"])) {
            $this->errors['productPrice'] = "Invalid Price Please Enter the Correct Price!";
        }

        if (! Validation::textValidate($attributes['price'])) {
            $this->errors['productPrice'] = "Invalid Price!";
        }

        if ($attributes['category'] === "Clothies-Category" || $attributes['category'] === "Technology-Category") {
            if (! Validation::arrayValidate($attributes['colors'] ?? null)) {
                $this->errors['productColor'] = "Invalid Color Please Choose the Correct Color for Clothies!";
            }
        }

        if ($attributes['category'] === "Clothies-Category") {
            if (! Validation::arrayValidate($attributes['sizes'] ?? null)) {
                $this->errors['productSize'] = "Invalid Size Please Choose the Correct Size for Clothies!";
            }
        }

        if (! Validation::imageHandle($image, $attributes['product_name'])) {
            $this->errors['productImage'] = "Please Enter Correct Extension (png, jpg, jpeg)!";
        }
    }
}
