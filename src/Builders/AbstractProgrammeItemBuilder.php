<?php
declare(strict_types = 1);
namespace App\Builders;

use BBC\ProgrammesPagesService\Domain\ValueObject\PartialDate;
use DateTimeImmutable;

abstract class AbstractProgrammeItemBuilder extends AbstractProgrammeBuilder
{
    /**
     * @see \BBC\ProgrammesPagesService\Domain\Enumeration\MediaTypeEnum
     * @var string
     */
    protected $mediaType;

    /** @var int */
    protected $segmentEventCount;

    /** @var PartialDate|null */
    protected $releaseDate;

    /** @var int|null */
    protected $duration;

    /** @var DateTimeImmutable|null */
    protected $streamableFrom;

    /** @var DateTimeImmutable|null */
    protected $streamableUntil;

    protected function __construct()
    {
        parent::__construct();
        $this->mediaType = 'audio_video';
        $this->segmentEventCount = 19;
        $this->duration = 6400;
    }

    /**
     * @see \BBC\ProgrammesPagesService\Domain\Enumeration\MediaTypeEnum
     */
    public function withMediaType(string $mediatype)
    {
        $this->mediaType = $mediatype;
        return $this;
    }

    public function withSegmentEventCount(int $segmentEventCount)
    {
        $this->segmentEventCount = $segmentEventCount;
        return $this;
    }


    public function withReleaseDate(?PartialDate $releaseDate)
    {
        $this->releaseDate = $releaseDate;
        return $this;
    }

    public function withDuration(?int $duration)
    {
        $this->duration = $duration;
        return $this;
    }

    public function withStreamableFrom(?DateTimeImmutable $streamableFrom)
    {
        $this->streamableFrom = $streamableFrom;
        return $this;
    }

    public function withStremableUntil(?DateTimeImmutable $streamableUntil)
    {
        $this->streamableUntil = $streamableUntil;
        return $this;
    }
}
