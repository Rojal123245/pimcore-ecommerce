<?php

namespace App\Controller\Resources\Jobs;

use App\Controller\Resources\AbstractResource;
use Carbon\Carbon;
use Pimcore\Model\DataObject\Vacancy;
use Pimcore\Model\Element\ElementInterface;

class JobResource extends AbstractResource
{

    public function getResource(Vacancy|ElementInterface $element): array
    {
        return [
            'title' => $element->getTitle(),
            'location' => $element?->getLocation() ? (new JobLocationResource())->toArray([$element->getLocation()]) : null,
            'published_at' => Carbon::parse($element->getPublished_at())->format('Y-m-d'),
            'link' => $element->getFile()->getFullPath()
        ];
    }
}
