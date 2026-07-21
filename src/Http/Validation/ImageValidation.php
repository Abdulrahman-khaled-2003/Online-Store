<?php

namespace App\Http\Validation;

use App\Core\Validation;

class ImageValidation extends Validation
{
    private $imgTmp;
    private $imgExtension;

    public function isFoundImage($image)
    {
        return ($image['image']['name'] === "") ? false : true;
    }

    public function isCorrectTypeOfImage($imgTmp)
    {
      return checkTypeOfImage($imgTmp);
    }

    public function isCorrectSizeOfImage($imgSize, $maxSize = 5242880)
    {
        return ($imgSize >  $maxSize) ? false : true;
    }

    private function parseImage($image)
    {
        $imgData = $image['image'];
        $imgName = $imgData['name'];
        $this->imgTmp = $imgData['tmp_name'];
        $this->imgExtension = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
    }

    private function imageHandler($image, $name, $imgPath)
    {
        $this->parseImage($image);
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
