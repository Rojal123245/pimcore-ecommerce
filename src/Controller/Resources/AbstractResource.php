<?php

namespace App\Controller\Resources;

use Pimcore\Model\Element\ElementInterface;

abstract class AbstractResource
{

    /**
     * @param ElementInterface[] $resources
     * @return array
     */
    public function toArray(array $resources): array
    {
        $data = [];
        foreach ($resources as $element) {
            $data[] = $this->getResource($element);
        }

        return $data;
    }

    abstract public function getResource(ElementInterface $element): array;

}
