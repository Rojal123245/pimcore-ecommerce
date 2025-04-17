<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class NewsHighlightsAreaBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'News-Highlights';
    }

    public function getDescription(): string
    {
        return 'News-Highlights';
    }

}
