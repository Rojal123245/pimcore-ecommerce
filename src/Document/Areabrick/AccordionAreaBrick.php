<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class AccordionAreaBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'Akkordeon';
    }

    public function getDescription(): string
    {
        return 'Accordion';
    }

}
