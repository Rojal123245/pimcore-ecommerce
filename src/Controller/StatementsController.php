<?php

namespace App\Controller;

use Carbon\Carbon;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject\Statement;
use Pimcore\Model\DataObject\StatementCategory;
use Pimcore\Tool;
use Pimcore\Translation\Translator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StatementsController extends FrontendController
{
    public function __construct(protected Translator $translator)
    {
    }

    #[Route('/api/statements/categories', name: 'api_statements_categories')]
    public function getCategories(Request $request): JsonResponse
    {
        $locale = $request->get('locale');
        if (!in_array($locale, Tool::getValidLanguages())) {
            $locale = 'de';
        }
        $categories = StatementCategory::getList();
        $categories->setLocale($locale);

        $statements = new Statement\Listing();
        $statements->setLocale($locale);

        $years = [
            [
                'key' => $this->translator->trans('Choose a year'),
                'value' => ''
            ]
        ];

        foreach ($statements->getData() as $statement) {
            $years[$statement->getDate()->year] = [
                'key' => $statement->getDate()->year,
                'value' => $statement->getDate()->year
            ];
        }

        return new JsonResponse([
            'categories' => array_map(function ($category) use ($locale) {
                return [
                    'id' => $category->getId(),
                    'name' => $category->getName($locale),
                ];
            }, $categories->load()),
            'years' => array_values($years)
        ]);

    }

    #[Route('/api/statements', name: 'api_statements')]
    public function getStatements(Request $request): JsonResponse
    {
        $category = $request->get('category');
        $date = $request->get('date');

        $locale = $request->get('locale');
        $statements = new Statement\Listing();

        if (in_array($locale, Tool::getValidLanguages())) {
            $statements->setLocale($locale);
        } else {
            $locale = 'de';
        }

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
                $statements->addConditionParam(implode(' OR ', $parts));
            }
        }

        $years = explode(',', trim($date));
        if (count($years) > 0) {
            $parts = [];
            foreach ($years as $year) {
                if (empty($year)) {
                    continue;
                }
                $date = Carbon::createFromDate($year, 1, 1);
                $startDate = $date->startOfYear()->timestamp;
                $endDate = $date->endOfYear()->timestamp;

                $parts[] = "(`date` BETWEEN $startDate AND $endDate)";
            }
            if (!empty($parts)) {
                $statements->addConditionParam(implode(' OR ', $parts), []);
            }
        }


        return new JsonResponse([
            'statements' => array_map(function (Statement $statement) use ($locale) {
                return [
                    'id' => $statement->getId(),
                    //'icon' => $statement->getIcon()?->getThumbnail('statements-icon')->getHtml(),
                    'icon' => '<img src="' . $statement->getIcon()->getFullPath() . '" width="76">',
                    //'altIcon' => $statement->getIconColored()?->getThumbnail('statements-icon')->getHtml(),
                    'altIcon' => '<img src="' . $statement->getIconColored() . '" width="76">',
                    'content' => $statement->getContent($locale),
                    'title' => $statement->getTitle($locale),
                    'date' => $statement->getDate()?->year,
                    'link' => $statement->getLink($locale)?->getHref(),
                    'target' => $statement->getLink($locale)?->getTarget(),
                    'categories' => array_map(function ($category) use ($locale) {
                        return $category->getName($locale);
                    }, $statement->getCategories()),
                ];
            }, $statements->load())
        ]);

    }

}
