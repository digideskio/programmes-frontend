<?php
declare(strict_types = 1);
namespace App\Ds2013\Presenters\Domain\Programme;

use App\Ds2013\Presenters\Domain\Programme\BroadcastSubPresenters\BroadcastProgrammeBodyPresenter;
use App\Ds2013\Presenters\Domain\Programme\SubPresenters\ProgrammeBodyPresenter;
use App\DsShared\Helpers\HelperFactory;
use BBC\ProgrammesPagesService\Domain\Entity\Broadcast;
use BBC\ProgrammesPagesService\Domain\Entity\Programme;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * This is a small override of the programme presenter that handles
 * whether or not something is a repeat. Yeah. That's it. All this boilerplate for that.
 */
class BroadcastProgrammePresenter extends ProgrammePresenter
{
    /** @var Broadcast */
    private $broadcast;

    private $additionalOptions = [];

    /**
     * Define options needed by all broadcast sub-presenters too
     *
     * @var array
     */
    private $additionalSharedOptions = [];

    public function __construct(
        UrlGeneratorInterface $router,
        HelperFactory $helperFactory,
        Broadcast $broadcast,
        Programme $programme,
        array $options = []
    ) {
        $options = array_merge($this->additionalOptions, $options);
        $this->sharedOptions = array_merge($this->sharedOptions, $this->additionalSharedOptions);
        parent::__construct($router, $helperFactory, $programme, $options);
        $this->broadcast = $broadcast;
    }

    public function getProgrammeBodyPresenter(): ProgrammeBodyPresenter
    {
        return new BroadcastProgrammeBodyPresenter(
            $this->router,
            $this->helperFactory->getPlayTranslationsHelper(),
            $this->broadcast,
            $this->programme,
            $this->subPresenterOptions('body_options')
        );
    }

    public function getTemplatePath(): string
    {
        return '@Ds2013/Presenters/Domain/Programme/programme.html.twig';
    }

    public function getTemplateVariableName(): string
    {
        return 'programme';
    }
}
