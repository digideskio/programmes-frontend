<?php

namespace App\Builders;

use BBC\ProgrammesPagesService\Domain\Entity\Options;
use BBC\ProgrammesPagesService\Domain\Entity\Series;
use BBC\ProgrammesPagesService\Domain\ValueObject\Pid;
use BBC\ProgrammesPagesService\Domain\ValueObject\Synopses;

class SeriesBuilder extends AbstractProgrammeContainerBuilder implements BuilderInterface
{
    private function __construct()
    {
        $this->aggregatedBroadcastsCount =2;
        $this->aggregatedEpisodesCount = 4;
        $this->availableClipsCount = 3;
        $this->availableEpisodesCount = 2;
        $this->isPodcastable = true;
        $this->expectedChildCount = 2;
        $this->dbAncestryIds = [1212];
        $this->pid = new Pid('d00744wz');
        $this->title = 'My series title';
        $this->searchTitle = 'My search title';
        $this->synopses = new Synopses('My short synopsis', 'my a very medium no too much synopsis', 'my extremely boring and endless text for my long synopsis');
        $this->image = ImageBuilder::default()->build();
        $this->promotionsCount = 10;
        $this->relatedLinksCount = 5;
        $this->hasSupportingContent = false;
        $this->isStreamable = true;
        $this->isStreamableAlternate = true;
        $this->contributionsCount = 2;
        $this->aggregatedGalleriesCount = 3;
        $this->options = new Options();
    }

    /**
     * Create a builder with default state.
     * This default state reduce the amount of steps necessary when trying to get a final entity in an specified state
     */
    public static function default()
    {
        return new self();
    }

    /**
     * Given that we have the desired state, then create the real entity we want
     */
    public function build(): Series
    {
        return new Series(
            $this->dbAncestryIds,
            $this->pid,
            $this->title,
            $this->searchTitle,
            $this->synopses,
            $this->image,
            $this->promotionsCount,
            $this->relatedLinksCount,
            $this->hasSupportingContent,
            $this->isStreamable,
            $this->isStreamableAlternate,
            $this->contributionsCount,
            $this->aggregatedBroadcastsCount,
            $this->aggregatedEpisodesCount,
            $this->availableClipsCount,
            $this->availableEpisodesCount,
            $this->aggregatedGalleriesCount,
            $this->isPodcastable,
            $this->options,
            // optionals
            $this->parent,
            $this->position,
            $this->masterBrand,
            $this->genres,
            $this->formats,
            $this->firstBroadcastDate,
            $this->expectedChildCount
        );
    }
}
