<?php

namespace App\Twig;

use Pimcore\Model\DataObject\NewsCategory;
use Pimcore\Model\Document\Editable\Relations;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Pimcore\Model\DataObject\News\Listing as NewsListing;

class SwissEngineeringTwigHelpers extends AbstractExtension {

    public function getFunctions(): array
    {
        return [
            new TwigFunction('swiss_engineering_news', [$this, 'getNewsListing']),
            new TwigFunction('swiss_engineering_get_news_by_categories', [$this, 'getNewsByCategories']),
            new TwigFunction('swiss_engineering_get_news_categories', [$this, 'getNewsCategories'])
        ];
    }


    /**
     * This is used by News Teaser Area brick
     * @return NewsListing
     */
    public function getNewsListing(): NewsListing
    {
        $listing = new NewsListing();
        $listing->setOrder('desc');
        $listing->setOrderKey('creationDate');
        return $listing;
    }

    public function getNewsByCategories(Relations $relation): array
    {
        $news = [];
        /** @var NewsCategory $element */
        foreach ($relation->getData() as $element) {
            $elements = $element->getNews();

            foreach ($elements as $item) {
                $news[$item->getId()] = $item;
            }
        }

        return $news;
    }

    public function getNewsCategories(): array
    {
        return NewsCategory::getList()->load();
    }

}
