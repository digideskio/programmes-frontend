<?php
declare(strict_types = 1);
namespace App\Ds2013;

use App\Ds2013\Helpers\HelperFactory;
use App\Ds2013\Molecule\DateList\DateListPresenter;
use App\Ds2013\Molecule\Image\ImagePresenter;
use App\Ds2013\Organism\Broadcast\BroadcastPresenter;
use App\Ds2013\Organism\Programme\CollapsedBroadcastProgrammePresenter;
use App\Ds2013\Organism\Programme\ProgrammePresenter;
use BBC\ProgrammesPagesService\Domain\Entity\CollapsedBroadcast;
use BBC\ProgrammesPagesService\Domain\Entity\Image;
use BBC\ProgrammesPagesService\Domain\Entity\Programme;
use BBC\ProgrammesPagesService\Domain\Entity\Service;
use Cake\Chronos\Chronos;
use App\Translate\TranslateProvider;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Ds2013 Factory Class for creating presenters.
 *
 * This abstraction shall allow us to have a single entry point to create any
 * Presenter. This is particularly valuable in two cases:
 * 1) When presenters require Translate, we have a single point to inject it
 * 2) When we have multiple Domain objects that should all be rendered using the
 *    same template. This factory allows us to choose the correct presenter for
 *    a given domain object.
 *
 * This class has create methods for all molecules, organisms and templates
 * which have presenters.
 * Each respective group MUST have the methods kept in alphabetical order
 *
 * To instantiate Ds2013 you MUST pass it an instance of TranslateProvider
 * All presenters MUST be created using this factory.
 * All presenters MUST call the base Presenter __construct method
 *
 */
class PresenterFactory
{
    /** @var TranslateProvider */
    private $translateProvider;

    /** @var UrlGeneratorInterface */
    private $router;

    /** @var HelperFactory */
    private $helperFactory;

    public function __construct(TranslateProvider $translateProvider, UrlGeneratorInterface $router, HelperFactory $helperFactory)
    {
        $this->translateProvider = $translateProvider;
        $this->router = $router;
        $this->helperFactory = $helperFactory;
    }

    /**
     * Molecules
     */
    public function dateListPresenter(
        Chronos $datetime,
        Service $service,
        array $options = []
    ): DateListPresenter {
        return new DateListPresenter(
            $datetime,
            $service,
            $options
        );
    }

    public function imagePresenter(
        Image $image,
        int $defaultWidth,
        $sizes,
        array $options = []
    ): ImagePresenter {
        return new ImagePresenter(
            $image,
            $defaultWidth,
            $sizes,
            $options
        );
    }

    /**
     * Organisms
     */

    /**
     * Create a programme presenter class
     */
    public function programmePresenter(
        Programme $programme,
        array $options = []
    ): ProgrammePresenter {
        return new ProgrammePresenter(
            $this->router,
            $this->helperFactory,
            $programme,
            $options
        );
    }

    public function collapsedBroadcastProgrammePresenter(
        CollapsedBroadcast $collapsedBroadcast,
        ?Programme $programme = null,
        array $options = []
    ): CollapsedBroadcastProgrammePresenter {
        if (!$programme) {
            $programme = $collapsedBroadcast->getProgrammeItem();
        }
        return new CollapsedBroadcastProgrammePresenter(
            $this->router,
            $this->helperFactory,
            $collapsedBroadcast,
            $programme,
            $options
        );
    }

    public function broadcastPresenter(
        $broadcast,
        ?CollapsedBroadcast $collapsedBroadcast,
        array $options = []
    ): BroadcastPresenter {
        return new BroadcastPresenter(
            $broadcast,
            $collapsedBroadcast,
            $options
        );
    }
}
