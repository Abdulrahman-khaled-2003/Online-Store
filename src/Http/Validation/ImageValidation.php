<?php

namespace App\Http\Validation;

use App\Core\Validation;

class ImageValidation extends Validation
{
    private $imgTmp;
    private $imgSize;
    private $imgExtension;

    private function parseImage($image)
    {
        $imgData = $image['image'];
        $this->imgSize = $imgData['size'];
        $imgName = $imgData['name'];
        $this->imgTmp = $imgData['tmp_name'];
        $this->imgExtension = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
    }

    private function imageHandler($image, $name, $imgPath)
    {
        $this->parseImage($image);

        if (! $this->isCorrectImage($this->imgTmp) || ! $this->isSizeImage($this->imgSize)) {
            return false;
        }
        $imgPath = base_path("../public/assets/images/{$imgPath}/");
        $this->fileExists($imgPath);
        return  moveUploadedFile($this->imgTmp, $imgPath . $name . "." . $this->imgExtension);
    }

    public function  isValidProductImage($image, $productName)
    {
        return $this->imageHandler($image, $productName, "Products");
    }

    public function isValidCategoryImage($image, $categoryName)
    {
        return $this->imageHandler($image, $categoryName, "Categories");
    }
}
