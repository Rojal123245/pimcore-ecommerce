<?php
namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class ShareAreaBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'Share';
    }

    public function getDescription(): string
    {
        return 'Share';
    }
}
