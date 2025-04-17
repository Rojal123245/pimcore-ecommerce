<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class DividerAreaBrick extends AbstractTemplateAreabrick
{
    
    public function getName(): string
    {
        return 'Divider';
    }

    public function getDescription(): string
    {
        return 'Divider';
    }

}
