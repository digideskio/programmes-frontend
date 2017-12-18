<?php

namespace App\Builders;

use BBC\ProgrammesPagesService\Domain\Entity\Brand;

class BrandBuilder extends AbstractProgrammeContainerBuilder
{
    /**
     * Given that we have the desired state, then create the real entity we want
     */
    public function build(): Brand
    {
        return new Brand(
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
