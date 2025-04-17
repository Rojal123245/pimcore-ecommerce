<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class BulletPointsAreaBrick extends AbstractTemplateAreabrick
{

    public function getName(): string
    {
        return 'Text';
    }

    public function getDescription(): string
    {
        return 'Bulletpoints';
    }

}
