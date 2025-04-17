<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class CardTeaserAreaBrick extends AbstractTemplateAreabrick
{

    public function getName(): string
    {
        return 'Card Teaser';
    }

    public function getDescription(): string
    {
        return 'Card Teaser';
    }

}
