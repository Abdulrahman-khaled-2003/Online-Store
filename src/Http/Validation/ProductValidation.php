<?php

namespace App\Http\Validation;

use App\Core\Validation;
use App\Http\Validation\ImageValidation;

class ProductValidation extends Validation
{
    public function __construct(array $attributes, array $image)
    {
        $this->method = $attributes['_method'] ?? [];

        if (! $this->textValidate($attributes['product_name'])) {
            $this->errors['productName'] = "Invalid Product Name!";
        }

        if (! $this->textValidate($attributes['description'])) {
            $this->errors['productDescription'] = "Invalid Description!";
        }

        if (! $this->isNumeric($attributes["price"])) {
            $this->errors['productPrice'] = "Invalid Price Please Enter the Correct Price!";
        }

        if (! $this->textValidate($attributes['price'])) {
            $this->errors['productPrice'] = "Invalid Price!";
        }

        if (checkColor($attributes["category"])) {
            if (! $this->isNotEmptyArray($attributes['colors'] ?? null)) {
                $this->errors['productColor'] = "Invalid Color Please Choose the Correct Color for Clothies!";
            }
        }

        if (checkSize($attributes['category'])) {
            if (! $this->isNotEmptyArray($attributes['sizes'] ?? null)) {
                $this->errors['productSize'] = "Invalid Size Please Choose the Correct Size for Clothies!";
            }
        }

        if ($this->method != "PUT") {
            if (! imageValidation()->isFoundImage($image)) {
                $this->errors['productImage'] = "Please Enter Image of Product!";
            } elseif (! imageValidation()->isCorrectTypeOfImage($image['image']['tmp_name'])) {
                $this->errors['productImage'] = "Invalid Extension Please Enter Correct Extension (PNG, JPG, JPEG)!";
            } elseif (! imageValidation()->isCorrectSizeOfImage($image['image']['size'])) {
                $this->errors['productImage'] = "Invalid Size, Size Must Be Under 5MB.";
            } else {
                imageValidation()->isValidProductImage($image, $attributes['product_name']);
            }
        }

        if ($this->method === "PUT" && $image['image']['name'] != "") {
            if (! imageValidation()->isCorrectTypeOfImage($image['image']['tmp_name'])) {
                $this->errors['productImage'] = "Invalid Extension Please Enter Correct Extension (PNG, JPG, JPEG)!";
            } elseif (! imageValidation()->isCorrectSizeOfImage($image['image']['size'])) {
                $this->errors['productImage'] = "Invalid Size, Size Must Be Under 5MB.";
            } else {
                imageValidation()->isValidProductImage($image, $attributes['product_name']);
            }
        }
    }
}
