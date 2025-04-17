<?php

namespace App\Model\Product;

use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\ClassDefinition;

class Product extends AbstractProduct
{
    protected $classId = "product";
    protected $className = "Product";

    public static function getById(mixed $id, array $params = []): ?static
    {
        if ($id === null) {
            return null;
        }
        
        $object = parent::getById($id, $params);
        
        if ($object instanceof static) {
            return $object;
        }
        
        return null;
    }

    public function getFormattedPrice(): string
    {
        return number_format($this->getPrice(), 2);
    }

    public function getStockStatus(): string
    {
        $quantity = $this->getQuantity();
        if ($quantity <= 0) {
            return 'out_of_stock';
        } elseif ($quantity <= 5) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    public function getThumbnailUrl(string $thumbnailName): string
    {
        if ($this->getImages() && count($this->getImages()) > 0) {
            return $this->getImages()[0]->getThumbnail($thumbnailName);
        }
        return ''; // or return a default image URL
    }
}
