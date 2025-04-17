<?php
namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class QuoteAreaBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'Quote';
    }

    public function getDescription(): string
    {
        return 'Quote';
    }
}
