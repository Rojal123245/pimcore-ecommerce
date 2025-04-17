<?php

namespace App\Controller\Resources\Jobs;

use App\Controller\Resources\AbstractResource;
use Pimcore\Model\DataObject\JobLocation;
use Pimcore\Model\Element\ElementInterface;

class JobLocationResource extends AbstractResource
{

    public function getResource(ElementInterface|JobLocation $element): array
    {
        return [
            'name' => $element->getName(),
            'id' => $element->getId()
        ];
    }
}
