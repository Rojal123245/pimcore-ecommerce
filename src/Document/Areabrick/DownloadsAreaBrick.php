<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class DownloadsAreaBrick extends AbstractTemplateAreabrick
{

    public function getName(): string
    {
        return 'Downloads';
    }

    public function getDescription(): string
    {
        return 'Downloads';
    }

}
