<?php
namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class CardsApartmentsAreaBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'Cards Wohnungen';
    }

    public function getDescription(): string
    {
        return 'Cards Apartments';
    }
}