<?php
declare(strict_types = 1);
namespace App\Controller;

use BBC\ProgrammesPagesService\Domain\Entity\CoreEntity;
use BBC\ProgrammesPagesService\Domain\Entity\Image;
use BBC\ProgrammesPagesService\Domain\ValueObject\Pid;
use BBC\ProgrammesPagesService\Service\ProgrammesService;
use BBC\ProgrammesPagesService\Service\PromotionsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StyleguideAmenController extends BaseController
{
    public function __invoke(string $action)
    {
        $methodName = $action . 'Action';

        if ($methodName == 'introAction' || !method_exists($this, $methodName)) {
            throw $this->createNotFoundException(sprintf(
                'Styleguide Action not found. Expected one of %s but got "%s"',
                '"' . implode('", "', $this->validActionNames()) . '"',
                $action
            ));
        }

        return $this->forward(self::class . '::' . $methodName);
    }

    public function introAction()
    {
        return $this->renderWithChrome('styleguide-amen/intro.html.twig');
    }

    public function promotionAction(
        ProgrammesService $programmesService,
        PromotionsService $promotionsService
    ) {
        $context = $programmesService->findByPidFull(new Pid('b01mmqtt'));
        $promos = $promotionsService->findActivePromotionsByContext($context);

        $promoOfCoreEntity = null;
        $promoOfImage = null;

        foreach ($promos as $promo) {
            if (!$promoOfCoreEntity && $promo->getPromotedEntity() instanceof CoreEntity) {
                $promoOfCoreEntity = $promo;
            }

            if (!$promoOfImage && $promo->getPromotedEntity() instanceof Image) {
                $promoOfImage = $promo;
            }
        }

        return $this->renderWithChrome('styleguide-amen/promotion.html.twig', [
            'promoOfCoreEntity' => $promoOfCoreEntity,
            'promoOfImage' => $promoOfImage,
        ]);
    }

    private function validActionNames(): array
    {
        // Valid actions are based upon the methods in this class that end
        // with 'Action', with the exception of 'introAction'
        $validActions = [];

        foreach (get_class_methods($this) as $method) {
            if ($method == 'introAction') {
                continue;
            }

            if (substr($method, -6) == 'Action') {
                $validActions[] = substr($method, 0, -6);
            }
        }

        return $validActions;
    }
}
