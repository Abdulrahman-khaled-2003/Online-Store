<?php

namespace App\Http\Validation;

use App\Core\Validation;

require base_path("Core/Validation.php");
require "ImageHandler.php";

class CategoryValidation extends Validation
{
    public function __construct(array $attributes, array $image)
    {

        $this->method = $attributes['_method'] ?? [];

        if (! $this->textValidate($attributes['category-name'])) {
            $this->errors['categoryName'] = "Invalid Category Name!";
        }

        if (! $this->textValidate($attributes['category-desc'])) {
            $this->errors['categoryDescription'] = "Invalid Category Description!";
        }

        if ($this->method != "PUT") {
            if (! $this->isFoundImage($image)) {
                $this->errors['categoryImage'] = "Please Enter Image of Category!";
            } elseif (! (new ImageHandler)->isValidCategoryImage($image, $attributes['category-name'])) {
                $this->errors['categoryImage'] = "Invalid Extension Please Enter Correct Extension (PNG, JPG, JPEG)!";
            }
        }

        if ($this->method === "PUT" && $image['image']['name'] != "") {
            if (! (new ImageHandler)->isValidCategoryImage($image, $attributes['category-name'])) {
                $this->errors['categoryImage'] = "Invalid Extension Please Enter Correct Extension (PNG, JPG, JPEG)!";
            }
        }
    }
}
