<?php

namespace App\Http\Validation;

use App\Core\Validation;

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

        if (! $this->checkNum($attributes["price"])) {
            $this->errors['productPrice'] = "Invalid Price Please Enter the Correct Price!";
        }

        if (! $this->textValidate($attributes['price'])) {
            $this->errors['productPrice'] = "Invalid Price!";
        }

        if (checkColor($attributes["category"])) {
            if (! $this->arrayValidate($attributes['colors'] ?? null)) {
                $this->errors['productColor'] = "Invalid Color Please Choose the Correct Color for Clothies!";
            }
        }

        if (checkSize($attributes['category'])) {
            if (! $this->arrayValidate($attributes['sizes'] ?? null)) {
                $this->errors['productSize'] = "Invalid Size Please Choose the Correct Size for Clothies!";
            }
        }

        if ($this->method != "PUT") {
            if (! $this->isImage($image)) {
                $this->errors['productImage'] = "Please Enter Image of Product!";
            } elseif (! $this->moveProductImage($image, $attributes['product_name'])) {
                $this->errors['productImage'] = "Invalid Extension Please Enter Correct Extension (PNG, JPG, JPEG)!";
            }
        }

        if ($this->method === "PUT" && $image['image']['name'] != "") {
            if (! $this->moveProductImage($image, $attributes['product_name'])) {
                $this->errors['productImage'] = "Invalid Extension Please Enter Correct Extension (PNG, JPG, JPEG)!";
            }
        }
    }
}
