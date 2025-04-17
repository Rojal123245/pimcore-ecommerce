<?php

namespace App\Controller\Resources\News;

use App\Controller\Resources\AbstractResource;
use App\Service\LinkGenerator\NewsLinkGenerator;
use Pimcore\Bundle\SeoBundle\Sitemap\UrlGenerator;
use Pimcore\Model\DataObject\News;
use Pimcore\Model\Element\ElementInterface;

class NewsResource extends AbstractResource
{
    public function __construct(protected NewsLinkGenerator $urlGenerator)
    {
    }

    public function getResource(ElementInterface|News $element): array
    {
        return [
            'id' => $element->getId(),
            'image' => $element->getBanner()?->getThumbnail('news-teaser-thumbnail')->getHtml([
                'imgAttributes' => [
                    'class' => 'w-full'
                ]
            ]) ?? '',
            'title' => $element->getTitle(),
            'excerpt' => $element->getExcerpt(),
            'link' => $this->urlGenerator->generate($element)
        ];
    }
}
