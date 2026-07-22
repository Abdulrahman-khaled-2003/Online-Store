<?php

namespace App\Core;

Abstract class Migration{
        abstract public function up() : void;
        abstract public function down() : void;
}