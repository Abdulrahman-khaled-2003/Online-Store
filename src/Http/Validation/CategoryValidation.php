<?php

namespace App\Http\Validation;

use App\Core\Validation;

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
            if (! $this->isImage($image)) {
                $this->errors['categoryImage'] = "Please Enter Image of Category!";
            } elseif (! $this->moveCategoryImage($image, $attributes['category-name'])) {
                $this->errors['categoryImage'] = "Invalid Extension Please Enter Correct Extension (PNG, JPG, JPEG)!";
            }
        }

        if ($this->method === "PUT" && $image['image']['name'] != "") {
            if (! $this->moveCategoryImage($image, $attributes['category-name'])) {
                $this->errors['categoryImage'] = "Invalid Extension Please Enter Correct Extension (PNG, JPG, JPEG)!";
            }
        }
    }
}
