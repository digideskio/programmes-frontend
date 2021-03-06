<?php
declare(strict_types=1);

namespace App\Controller\Partial;

use App\Controller\BaseController;
use App\ExternalApi\Recipes\Service\RecipesService;
use Symfony\Component\HttpFoundation\Request;

class RecipesController extends BaseController
{
    public function __invoke(
        RecipesService $recipesService,
        Request $request,
        string $pid
    ) {
        $apiResponse = $recipesService->fetchRecipesByPid($pid);

        // if there are no recipes, don't display anything
        if ($apiResponse->getTotal() === 0 || !$apiResponse->getRecipes()) {
            // We don't throw a 404 directly here because we want to set the cache expiry time. This partial is only
            // used in brand pages, and doesn't show up if the response is a 404. Therefore, we can cache it for a while
            return $this->response()->setStatusCode(404)->setMaxAge(60);
        }

        $showImage = true;

        // Only show image if all recipes have images. Otherwise, the cards without images look very weird
        foreach ($apiResponse->getRecipes() as $recipe) {
            $showImage &= $recipe->getImage() ? true : false;
        }

        // Cache for 5 minutes
        $this->response()->setMaxAge(300);

        return $this->renderWithChrome('partial/recipes.html.twig', [
            'recipes' => $apiResponse->getRecipes(),
            'total' => $apiResponse->getTotal(),
            'pid' => $pid,
            'showImage' => $showImage,
        ]);
    }
}
