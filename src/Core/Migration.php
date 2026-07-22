<?php

namespace App\Core;

Abstract class Migration{
        public function __construct(public Database $db){
            //
        }
        
        abstract public function up() : void;
        abstract public function down() : void;
}