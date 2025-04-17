<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class NewsListAreaBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'News-Liste';
    }

    public function getDescription(): string
    {
        return 'News-List';
    }

}
