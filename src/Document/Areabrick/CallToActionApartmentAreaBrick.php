<?php
namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class CallToActionApartmentAreaBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'CTA Wohnungen';
    }

    public function getDescription(): string
    {
        return 'Call To Action Apartment';
    }
}