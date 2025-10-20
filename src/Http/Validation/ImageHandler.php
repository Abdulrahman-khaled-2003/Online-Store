<?php

namespace App\Http\Validation;

use App\Core\Exception\FileNotFoundException;
use App\Core\Validation;

class ImageHandler extends Validation
{
    private $imgTmp;
    private $imgExtension;

    public function imageHandle($image): bool
    {
        $imgData = $image['image'];
        $imgName = $imgData['name'];
        $this->imgTmp = $imgData['tmp_name'];
        $extension = ["png", "jpg", "jpeg"];
        $this->imgExtension = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
        return $this->isCorrectImage($this->imgTmp);
    }

    public function  isValidProductImage($image, $productName)
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

    public function isValidCategoryImage($image, $categoryName)
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
