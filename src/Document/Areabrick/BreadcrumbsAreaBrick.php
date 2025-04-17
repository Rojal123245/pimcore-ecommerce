<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use Pimcore\Translation\Translator;

class BreadcrumbsAreaBrick extends AbstractTemplateAreabrick
{

    public function __construct(
        protected Translator $translator
    )
    {
    }

    public function getName(): string
    {
        return 'Breadcrumbs';
    }

    public function getDescription(): string
    {
        return 'Breadcrumbs';
    }

}
