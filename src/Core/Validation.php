<?php

namespace App\Core;

use App\Core\Exceptions\FileNotFoundException;

abstract class Validation
{
    private $imgTmp;
    private $imgExtension;
    protected $method;
    public $errors = [];

    protected function textValidate(string $value, int $min = 1, int $max = 100): bool
    {
        $value = trim($value);
        $value = stripslashes($value);
        return strlen($value) >= $min && strlen($value) <= $max;
    }

    protected function checkNum(string $number): bool
    {
        $number = (float) $number;
        return ($number <= 0) ? false : true;
    }

    protected function arrayValidate(?array $array)
    {
        return (!is_array($array) || empty($array)) ? false : true;
    }

    protected function isImage($image)
    {
        return ($image['image']['name'] === "") ? false : true;
    }

    private function imageHandle($image): bool
    {
        $imgData = $image['image'];
        $imgName = $imgData['name'];
        $this->imgTmp = $imgData['tmp_name'];
        $extension = ["png", "jpg", "jpeg"];
        $this->imgExtension = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
        return extensionValidate($this->imgExtension, $extension);
    }

    protected function moveProductImage($image, $productName)
    {
        if (! $this->imageHandle($image)) {
            return false;
        }
        $imgPath = base_path("../public/assets/images/Products/");
        if (! file_exists($imgPath)) {
            throw new FileNotFoundException("File of Image Not Found!");
        }
        return moveUploadedFile($this->imgTmp, $imgPath . $productName . "." . $this->imgExtension);
    }

    protected function moveCategoryImage($image, $categoryName)
    {
        if (! $this->imageHandle($image)) {
            return false;
        }
        $imgPath = base_path("../public/assets/images/Categories/");
        if (! file_exists($imgPath)) {
            throw new FileNotFoundException("File of Image Not Found!");
        }
        return  moveUploadedFile($this->imgTmp, $imgPath . $categoryName . "." . $this->imgExtension);
    }
}
