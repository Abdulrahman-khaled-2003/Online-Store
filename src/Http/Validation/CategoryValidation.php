<?php

namespace App\Http\Validation;

use App\Core\Validation;

require base_path("Core/Validation.php");

class CategoryValidation extends Validation
{
    public $errors = [];

    public function __construct(array $attributes)
    {
        if (! $this->textValidate($attributes['category-name'])) {
            $this->errors['categoryName'] = "Invalid Category Name!";
        }

        if (! $this->textValidate($attributes['category-desc'])) {
            $this->errors['categoryDescription'] = "Invalid Category Description!";
        }

        if (! $this->textValidate($attributes['category-img'])) {
            $this->errors['categoryImage'] = "Invalid Category Image!";
        }

        // if (! Validation::imageHandle($attributes['category-img'])) {
        //     $this->errors['categoryImage'] = "Invalid Extension!";
        // }
    }
}
