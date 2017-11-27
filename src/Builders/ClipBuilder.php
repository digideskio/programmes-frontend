<?php
declare(strict_types = 1);
namespace App\Builders;

use BBC\ProgrammesPagesService\Domain\Entity\Clip;
use BBC\ProgrammesPagesService\Domain\Entity\Options;
use BBC\ProgrammesPagesService\Domain\ValueObject\Pid;
use BBC\ProgrammesPagesService\Domain\ValueObject\Synopses;

class ClipBuilder extends AbstractProgrammeItemBuilder implements BuilderInterface
{
    /**
     * ClipBuilder constructor.
     */
    private function __construct()
    {
        $this->dbAncestryIds = [1212];
        $this->pid = new Pid('d00744wz');
        $this->title = 'default title for default clip';
        $this->searchTitle = 'this is a default clip';
        $this->synopses = new Synopses('My short synopsis', 'my a very medium no too much synopsis', 'my extremely boring and endless text for my long synopsis');
        $this->image = ImageBuilder::default()->build();
        $this->promotionsCount = 10;
        $this->relatedLinksCount = 5;
        $this->hasSupportingContent = false;
        $this->isStreamable = true;
        $this->isStreamableAlternate = true;
        $this->contributionsCount = 2;
        $this->mediaType = 'clip';
        $this->segmentEventCount = 19;
        $this->aggregatedGalleriesCount = 2;
        $this->options = new Options();
    }

    public static function default()
    {
        return new self();
    }

    public function build(): Clip
    {
        return new Clip(
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
