<?php

namespace App\Http\Validation;

use App\Http\Validation\ImageValidation;
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
            if (! $this->isFoundImage($image)) {
                $this->errors['categoryImage'] = "Please Enter Image of Category!";
            } elseif (! imageValidation()->isCorrectTypeOfImage($image['image']['tmp_name'])) {
                $this->errors['categoryImage'] = "Invalid Extension Please Enter Correct Extension (PNG, JPG, JPEG)!";
            } elseif (! imageValidation()->isCorrectSizeOfImage($image['image']['size'])) {
                $this->errors['categoryImage'] = "Invalid Size, Size Must Be Under 5MB.";
            } else {
                imageValidation()->isValidCategoryImage($image, $attributes['category-name']);
            }
        }

        if ($this->method === "PUT" && $image['image']['name'] != "") {
            if (! imageValidation()->isCorrectTypeOfImage($image['image']['tmp_name'])) {
                $this->errors['categoryImage'] = "Invalid Extension Please Enter Correct Extension (PNG, JPG, JPEG)!";
            } elseif (! imageValidation()->isCorrectSizeOfImage($image['image']['size'])) {
                $this->errors['categoryImage'] = "Invalid Size, Size Must Be Under 5MB.";
            } else {
                imageValidation()->isValidCategoryImage($image, $attributes['category-name']);
            }
        }
    }
}
