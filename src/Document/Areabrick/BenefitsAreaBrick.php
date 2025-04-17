<?php
namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class BenefitsAreaBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'Benefits';
    }

    public function getDescription(): string
    {
        return 'Benefits';
    }
}
