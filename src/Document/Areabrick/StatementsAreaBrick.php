<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class StatementsAreaBrick extends AbstractTemplateAreabrick
{

    public function getName(): string
    {
        return 'Stellungsnahmen';
    }

    public function getDescription(): string
    {
        return 'Statements';
    }

}
