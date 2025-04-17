<?php

namespace App\Controller;

use Pimcore\Bundle\AdminBundle\Controller\Admin\LoginController;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\Asset;
use Pimcore\Tool;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends FrontendController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function defaultAction(Request $request): Response
    {
        return $this->render('default/default.html.twig');
    }

    #[Route('/', name: 'home')]
    public function homeAction(Request $request): RedirectResponse
    {
        $acceptedLanguages = $request->getLanguages();
        $locale = 'de';
        foreach ($acceptedLanguages as $lang) {
            if (in_array($lang, Tool::getValidLanguages())) {
                $locale = $lang;
                break;
            }
        }

        return new RedirectResponse('/'.$locale);
    }

    /**
     * Forwards the request to admin login
     */
    public function loginAction(): Response
    {
        return $this->forward(LoginController::class.'::loginCheckAction');
    }

    public function errorPageAction(): Response
    {
        return $this->render('default/error.html.twig');
    }

    public function renderQuickLinksAction(): Response
    {
        return $this->render('default/quicklinks.html.twig');
    }

    public function renderFooterSnippetAction(): Response
    {
        return $this->render('partials/footer.html.twig');
    }

    #[Route('/download/{assetId}', name: 'asset-download')]
    public function downloadAsset(string $assetId): Response
    {
        $asset  = Asset::getById($assetId);
        if (!$asset instanceof Asset) {
            return $this->errorPageAction();
        }

        return new StreamedResponse(function () use ($asset) {
            $stream = $asset->getStream();
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $asset->getMimetype(),
            'Content-Disposition' => 'attachment; filename="'.$asset->getFilename().'"',
        ]);
    }

    public function renderNavigationLinksWithIconsAction(): Response
    {
        return $this->render('partials/navigation-link-with-icon.html.twig');
    }
}
