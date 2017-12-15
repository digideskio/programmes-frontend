<?php

declare(strict_types = 1);
namespace App\Builders;

Abstract class AbstractProgrammeContainerBuilder extends AbstractProgrammeBuilder
{
    /** @var int */
    protected $aggregatedBroadcastsCount;

    /** @var int */
    protected $aggregatedEpisodesCount;

    /** @var int */
    protected $availableClipsCount;

    /** @var int */
    protected $availableEpisodesCount;

    /** @var bool */
    protected $isPodcastable;

    /** @var int|null */
    protected $expectedChildCount;

    public function withAggregatedBroadcastsCount(int $aggregatedBroadcastsCount)
    {
        $this->aggregatedBroadcastsCount = $aggregatedBroadcastsCount;
        return $this;
    }

    public function withAggregatedEpisodesCount(int $aggregatedEpisodesCount)
    {
        $this->aggregatedEpisodesCount = $aggregatedEpisodesCount;
        return $this;
    }

    public function withAvailableClipsCount(int $availableClipsCount)
    {
        $this->availableClipsCount = $availableClipsCount;
        return $this;
    }

    public function withAvailableEpisodesCount(int $availableEpisodesCount)
    {
        $this->availableEpisodesCount = $availableEpisodesCount;
        return $this;
    }

    public function withIsPodcastable(bool $isPodcastable)
    {
        $this->isPodcastable = $isPodcastable;
        return $this;
    }

    public function withExpectedChildCount(int $expectedChildCount)
    {
        $this->expectedChildCount = $expectedChildCount;
        return $this;
    }
}
