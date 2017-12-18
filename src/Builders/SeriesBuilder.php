<?php

namespace App\Builders;

use BBC\ProgrammesPagesService\Domain\Entity\Series;

class SeriesBuilder extends AbstractProgrammeContainerBuilder
{
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * Create a builder with default state.
     * This default state reduce the amount of steps necessary when trying to get a final entity in an specified state
     */
    public static function default()
    {
        $self = new self();
        $self->withTitle('My series title')
            ->withSearchTitle('My search title');

        return $self;
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
