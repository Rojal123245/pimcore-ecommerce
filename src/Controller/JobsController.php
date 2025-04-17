<?php

namespace App\Controller;

use App\Controller\Resources\Jobs\JobLocationResource;
use App\Controller\Resources\Jobs\JobResource;
use Doctrine\DBAL\Query\QueryBuilder;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject\JobLocation;
use Pimcore\Model\DataObject\Vacancy;
use Pimcore\Translation\Translator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class JobsController extends FrontendController
{

    #[Route('/api/{locale}/jobs', methods: ['POST'])]
    public function getListOfJobs(Request $request, Translator $translator, $locale): JsonResponse
    {
        $filters = json_decode($request->getContent(), true);
        $listing = Vacancy::getList()->setLocale($locale);

        $locations = JobLocation::getList()->setLocale($locale);


        $jobs = $this->applyFilterToJobs($filters, $listing);

        $jobs = (new JobResource())->toArray($jobs->getData());
        return $this->json([
            'jobs' => $jobs,
            'locations' => (new JobLocationResource())->toArray($locations->getData()),
            'countText' => $translator->trans(':count results found', [
                ':count' => count($jobs),
            ], locale: $locale)
        ]);

    }

    #[Route('/api/{locale}/job-locations', methods: ['GET', 'POST'])]
    public function getJobLocations(Request $request, Translator $translator, $locale): JsonResponse
    {
        $locations = JobLocation::getList()->setLocale($locale);
        return $this->json([
            'locations' => [[
                'name' => $translator->trans('Select a Location', locale: $locale),
                'id' => ' '
            ], ...(new JobLocationResource())->toArray($locations->getData()), ]
        ]);

    }
    private function applyFilterToJobs(mixed $filters, Vacancy\Listing $listing): Vacancy\Listing
    {
        $listing->onCreateQueryBuilder(function (QueryBuilder $builder) {
            $locationTable = JobLocation::getList()->getDao()->getTableName();
            $vacancyTable=  Vacancy::getList()->getDao()->getTableName();
            $builder->join(
                $vacancyTable,
                $locationTable,
                'location',
                $vacancyTable.'.location__id = location.id'
            );
        });

        if (!is_array($filters)) {
            return $listing;
        }

        if (isset($filters['query'])) {
            $listing->addConditionParam("title like '%?%'", [
                $filters['query']
            ]);
        }

        if (!empty($filters['locations']) && $filters['locations'] !== ' ') {
            $listing->addConditionParam('location.id in (?)', explode(',', $filters['locations']));
        }

        if (isset($filters['sort_order'])) {
            $listing->setOrder($filters['sort_order']);
        }

        if (isset($filters['sort_by'])) {
            $table = Vacancy::getList()->getDao()->getTableName();
            match($filters['sort_by']) {
              'location' => $listing->setOrderKey('location.name'),
              'published_at' => $listing->setOrderKey($table . '.creationDate'),
                default => ''
            };
        }

        return $listing;

    }

}
