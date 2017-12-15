<?php
declare(strict_types = 1);
namespace App\Builders;

use BBC\ProgrammesPagesService\Domain\Entity\Episode;

class EpisodeBuilder extends AbstractProgrammeItemBuilder
{
    /** @var int */
    private $aggregatedBroadcastsCount;

    /** @var int */
    private $availableClipsCount;

    public function withAggregatedBroadcastsCount(int $aggregatedBroadcastsCount)
    {
        $this->aggregatedBroadcastsCount = $aggregatedBroadcastsCount;
        return $this;
    }

    public function withAvailableClipsCount(int $availableClipsCount)
    {
        $this->availableClipsCount = $availableClipsCount;
        return $this;
    }

    public static function default()
    {
        $self = new self();
        $self->withTitle('my episode title')
            ->withSearchTitle('my search episode title')
            ->withAggregatedBroadcastsCount(10)
            ->withAvailableClipsCount(10);

        return $self;
    }

    public function build(): Episode
    {
        return new Episode(
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
            $this->mediaType,
            $this->segmentEventCount,
            $this->aggregatedBroadcastsCount,
            $this->availableClipsCount,
            $this->aggregatedGalleriesCount,
            $this->options,
            // optionals
            $this->parent,
            $this->position,
            $this->masterBrand,
            $this->genres,
            $this->formats,
            $this->firstBroadcastDate,
            $this->releaseDate,
            $this->duration,
            $this->streamableFrom,
            $this->streamableUntil
        );
    }
}
