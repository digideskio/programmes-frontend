<?php
declare(strict_types = 1);
namespace App\Controller\Styleguide\Amen\Organism;

use App\Builders\ClipBuilder;
use App\Builders\EpisodeBuilder;
use App\Controller\BaseController;
use BBC\ProgrammesPagesService\Domain\Entity\Episode;

class ProgrammeController extends BaseController
{
    /**
        Programme
            ProgrammeContainer
                Brand
                Series

            ProgrammeITem
                Clipe
                Episode
                UnfetchedProgrammeItem

            UnfetchedProgramme
     */

    public function __invoke()
    {
        return $this->renderWithChrome('styleguide/amen/organism/programme.html.twig', [
            'episode' => $this->buildEpisodes(),
            'clip' => $this->buildClips(),
        ]);
    }

    /**
     * @return Episode[]
     */
    private function buildEpisodes(): array
    {
        $episode = EpisodeBuilder::default()->build();

        return [$episode];
    }

    private function buildClips()
    {
        $clip = ClipBuilder::default()->build();

        return [$clip];
    }
}
