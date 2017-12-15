<?php
declare(strict_types = 1);
namespace App\Builders;

use BBC\ProgrammesPagesService\Domain\Entity\Clip;

class ClipBuilder extends AbstractProgrammeItemBuilder implements BuilderInterface
{
    protected function __construct()
    {
        parent::__construct();

        $this->title = 'my clip title';
        $this->searchTitle = 'my search clip title';
        $this->duration = 6400;
    }

    public static function default()
    {
        return new self();
    }

    public function build()
    {
        return new CLip(
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
