<?php

namespace App\Http\Controllers;

use App\Core\Exception\RecordNotFoundException;
use App\Core\Session;
use App\Http\Validation\FormValidation;

require base_path("./Core/Exceptions/RecordNotFoundException.php");
require base_path("./Core/Session.php");
require base_path("Http/Validation/FormValidation.php");
require "Controller.php";

class ProductController extends Controller
{



    private function createProduct() {}

    private function editProduct(int $id) {}

    private function deleteProduct(int $id) {}

    private function getProduct(int $id){}

    private function getProducts() {
        
    }
}
