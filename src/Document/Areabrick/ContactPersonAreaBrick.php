<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class ContactPersonAreaBrick extends AbstractTemplateAreabrick
{

    public function getName(): string
    {
        return 'Kontaktperson';
    }

    public function getDescription(): string
    {
        return 'Contact person';
    }
    
}
