<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use Pimcore\Translation\Translator;

class HeroAreaBrick extends AbstractTemplateAreabrick
{

    public function __construct(
        protected Translator $translator
    )
    {}

    public function getName(): string
    {
        return 'Hero';
    }

    public function getDescription(): string
    {
        return 'Hero';
    }

    public function needsReload(): bool
    {
        return true;
    }

}
