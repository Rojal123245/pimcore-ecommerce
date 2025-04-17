<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use Pimcore\Model\Document\Editable\Area\Info;
use Pimcore\Model\DataObject;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LinkCards extends AbstractTemplateAreabrick
{

    public function getName(): string
    {
        return 'Link-Boxen';
    }

    public function getDescription(): string
    {
        return 'Link cards';
    }

    public function needsReload(): bool
    {
        return true;
    }

    public function getHtmlTagOpen(Info $info): string
    {
        return '';
    }

    public function getHtmlTagClose(Info $info): string
    {
        return '';
    }
    
}