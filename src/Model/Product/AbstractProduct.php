<?php

namespace App\Model\Product;

use Pimcore\Model\DataObject\Concrete;

abstract class AbstractProduct extends Concrete
{
    protected $classId = "PRODUCT";
    protected $className = "Product";
}