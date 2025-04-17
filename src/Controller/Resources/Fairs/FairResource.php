<?php

namespace App\Controller\Resources\Fairs;

use App\Controller\Resources\AbstractResource;
use Carbon\Carbon;
use Pimcore\Model\DataObject\Fair;
use Pimcore\Model\Element\ElementInterface;

class FairResource extends AbstractResource
{

    public function getResource(ElementInterface|Fair $element): array
    {

        $from = Carbon::parse($element->getFrom());
        $to = Carbon::parse($element->getTo());

        if ($from->isSameDay($to)) {
            $date = $from->format('d.m.Y');
        } else {
            $date = $from->format('d.m.Y') . ' - ' . $to->format('d.m.Y');
        }


        return [
            'title' => $element->getTitle(),
            'location' => $element->getLocation(),
            'date' => $date,
            'link' => $element->getLink()?->getHref()
        ];
    }
}
