<?php

namespace App\Builders;

use BBC\ProgrammesPagesService\Domain\Entity\Format;
use BBC\ProgrammesPagesService\Domain\Entity\Genre;

abstract class AbstractProgrammeBuilder extends AbstractCoreEntityBuilder
{
    /** @var bool */
    protected $hasSupportingContent;

    /** @var bool */
    protected $isStreamable;

    /** @var bool */
    protected $isStreamableAlternate;

    /** @var int|null */
    protected $position;

    /** @var Genre[]|null */
    protected $genres;

    /** @var Format[]|null */
    protected $formats;

    /** @var DateTimeImmutable|null */
    protected $firstBroadcastDate;

    /** @var int */
    protected $aggregatedGalleriesCount;

    protected function __construct()
    {
        parent::__construct();

        $this->hasSupportingContent = false;
        $this->isStreamable = true;
        $this->aggregatedGalleriesCount = 3;
        $this->isStreamableAlternate = true;
    }

    public function witHasSupportingContent(bool $hasSupportingContent)
    {
        $this->hasSupportingContent = $hasSupportingContent;
        return $this;
    }

    public function withIsStreamable(bool $isStremable)
    {
        $this->isStreamable = $isStremable;
        return $this;
    }

    public function withIsStreamableAlternate(bool $isStreamableAlternate)
    {
        $this->isStreamableAlternate = $isStreamableAlternate;
        return $this;
    }

    public function withPosition(?int  $position)
    {
        $this->position = $position;
        return $this;
    }

    public function withGenres(?array $genres)
    {
        $this->genres = $genres;
        return $this;
    }

    public function withFormats(?array $formats)
    {
        $this->formats = $formats;
        return $this;
    }

    public function withFirstBroadcastDate(?DateTimeImmutable $firstBroadcastDate)
    {
        $this->firstBroadcastDate = $firstBroadcastDate;
        return $this;
    }

    public function withAggregatedGalleriesCount(int $aggregatedGalleriesCount)
    {
        $this->aggregatedGalleriesCount = $aggregatedGalleriesCount;
        return $this;
    }
}
