<?php

namespace App\Http\Validation;

use App\Core\Validation;

require base_path("Core/Validation.php");

class CategoryValidation
{
    public $errors = [];

    public function __construct(array $attributes)
    {;
        if (! Validation::textValidate($attributes['category-name'])) {
            $this->errors['categoryName'] = "Invalid Category Name!";
        }

        if (! Validation::textValidate($attributes['category-desc'])) {
            $this->errors['categoryDescription'] = "Invalid Category Description!";
        }

        if (! Validation::textValidate($attributes['category-img'])) {
            $this->errors['categoryImage'] = "Invalid Category Image!";
        }
    }
}
