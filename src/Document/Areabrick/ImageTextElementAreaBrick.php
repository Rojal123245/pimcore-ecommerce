<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use Pimcore\Translation\Translator;

class ImageTextElementAreaBrick extends AbstractTemplateAreabrick
{
    public function __construct(
        protected Translator $translator
    )
    {}

    public function getName(): string
    {
        return 'Bild - Text';
    }

    public function getDescription(): string
    {
        return 'Image - Text';
    }

    public function needsReload(): bool
    {
        return true;
    }
}
