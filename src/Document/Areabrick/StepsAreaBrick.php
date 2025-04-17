<?php
namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class StepsAreaBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'Schritte';
    }

    public function getDescription(): string
    {
        return 'Steps';
    }
}