<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use Pimcore\Translation\Translator;

class ThemeTeaserAreaBrick extends AbstractTemplateAreabrick
{

    public function __construct(
        protected Translator $translator
    )
    {
    }

    public function getName(): string
    {
        return 'Thementeaser';
    }

    public function getDescription(): string
    {
        return 'Theme teaser';
    }

}
