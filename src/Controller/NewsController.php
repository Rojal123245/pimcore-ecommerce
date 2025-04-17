<?php

namespace App\Controller;

use App\Controller\Resources\News\NewsResource;
use App\Service\LinkGenerator\NewsLinkGenerator;
use Carbon\Carbon;
use Exception;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject\News;
use Pimcore\Tool;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends FrontendController
{

    /*
        You can rewrite this to your own Route notation if you want

        Only _n{id} at the end of the url should be the identifying part for getting the news object.
        Example of a detail link for a news object, they should both lead to the same news object:
            swissengineering.ch/de/news/this-is-my-news-for-2021_n123
            swissengineering.ch/de/news/x_n123
        Both of these links should have the same canonical: the one with the full title.
    */

    #[Route('/{_locale}/news/{title}_n{id}', name: 'news-detail', requirements: [
        'title' => '[\w-]+',
        'id' => '\d+'
    ])]
    public function detail(string $id)
    {
        $news = News::getById($id);
        if (!$news) {
            throw new NotFoundHttpException();
        }
        return $this->render('pages/news/details.html.twig', [
            'news' => $news
        ]);
    }

    #[Route('/api/{locale}/news', name: 'news-listing')]
    public function getNewsListing(string $locale, Request $request, NewsLinkGenerator $newsLinkGenerator): JsonResponse
    {
        $listing = News::getList();
        $listing->setOrder('desc');
        $listing->setOrderKey('creationDate');
        if (!in_array($locale, Tool::getValidLanguages())) {
            $locale = 'de';
        }
        $listing->setLocale($locale);

        $category = $request->query->get('category') ?? '';
        $query = $request->query->get('query') ?? null;
        $year = $request->query->get('date');

        if ($query) {
            $listing->addConditionParam("`title` LIKE " . $listing->quote('%' . $query . '%') );
            $listing->addConditionParam('`excerpt` like '. $listing->quote('%' . $query . '%'), concatenator: 'OR');
        }

        if ($year) {
            $years = array_filter(explode(',', $year), fn($y) => !empty($y));
            usort($years, fn($a, $b) => $a > $b);
            $start = $years[array_key_first($years)];
            $end = $years[array_key_last($years)];
            try {
                $start = Carbon::createFromFormat('Y', $start)->startOfYear();
                $end = Carbon::createFromFormat('Y', $end)->endOfYear();

                $listing->addConditionParam('`date` BETWEEN ? AND ?', [$start->timestamp, $end->timestamp]);
            } catch (Exception $e) {}
        }

        $categories = explode(',', $category);
        if (!empty($categories)) {
            $parts = [];
            foreach ($categories as $cat) {
                if (empty($cat)) {
                    continue;
                }
                $parts[] = "FIND_IN_SET('{$cat}', `category`) > 0";
            }
            if (!empty($parts)) {
                $listing->addConditionParam(implode(' OR ', $parts));
            }
        }

        $years = [];

        foreach ($listing->load() as $news) {
            $year = Carbon::parse($news->getDate())->year;
            $years[$year] = $year;
        }
        sort($years);
        return $this->json([
            'news' => (new NewsResource($newsLinkGenerator))->toArray($listing->load()),
            'date' => array_values($years)
        ]);
    }

}
