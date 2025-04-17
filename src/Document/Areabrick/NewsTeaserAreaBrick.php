<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class NewsTeaserAreaBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'News-Teaser';
    }

    public function getDescription(): string
    {
        return 'News-Teaser';
    }

}
