<?php

namespace App\Controller;

use App\Controller\Resources\Fairs\FairResource;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject\Fair;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FairController extends FrontendController
{

    #[Route('/api/{locale}/fairs')]
    public function getListOfFairs(string $locale, Request $request): JsonResponse
    {

        $fairs = Fair::getList()->setLocale($locale)->setOrderKey('creationDate')->setOrder('desc');

        $category = $request->query->get('categories') ?? null;

        if ($category) {
            $categories = explode(',', $category);
            if (!empty($categories)) {
                $parts = [];
                foreach ($categories as $cat) {
                    if (empty($cat)) {
                        continue;
                    }
                    $parts[] = "FIND_IN_SET('{$cat}', `categories`) > 0";
                }
                if (!empty($parts)) {
                    $fairs->addConditionParam(implode(' OR ', $parts));
                }
            }
        }


        $data = (new FairResource())->toArray($fairs->getData());
        return new JsonResponse([
            'fairs' => $data
        ]);
    }

}
