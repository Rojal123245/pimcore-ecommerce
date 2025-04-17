<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class HeroCarouselAreaBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'Hero Swiper';
    }

    public function needsReload(): bool
    {
        return true;
    }

    public function getDescription(): string
    {
        return 'Hero swiper';
    }

}
