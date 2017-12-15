<?php

namespace App\Builders;

use BBC\ProgrammesPagesService\Domain\Entity\Image;
use BBC\ProgrammesPagesService\Domain\Entity\MasterBrand;
use BBC\ProgrammesPagesService\Domain\Entity\Options;
use BBC\ProgrammesPagesService\Domain\Entity\Programme;
use BBC\ProgrammesPagesService\Domain\ValueObject\Pid;
use BBC\ProgrammesPagesService\Domain\ValueObject\Synopses;

abstract class AbstractCoreEntityBuilder
{
    /** @var Programme|null */
    protected $parent;

    /** @var Pid */
    protected $pid;

    /** @var int[] */
    protected $dbAncestryIds;

    /** @var string */
    protected $title;

    /** @var string */
    protected $searchTitle;

    /** @var Synopses  */
    protected $synopses;

    /** @var Image */
    protected $image;

    /** @var int */
    protected $promotionsCount;

    /** @var int */
    protected $relatedLinksCount;

    /** @var int */
    protected $contributionsCount;

    /** @var Options */
    protected $options;

    /** @var MasterBrand|null */
    protected $masterBrand;

    protected function __construct()
    {
        $this->dbAncestryIds = [1212];
        $this->pid = new Pid('d00744wz');
        $this->title = 'my programme title';
        $this->searchTitle = 'my programme title';
        $this->synopses = new Synopses('My short synopsis', 'my a very medium no too much synopsis', 'my extremely boring and endless text for my long synopsis');
        $this->image = ImageBuilder::default()->build();
        $this->promotionsCount = 10;
        $this->relatedLinksCount = 5;
        $this->contributionsCount = 2;
        $this->options = new Options();
    }

    public function withDbAncestryIds(array $dbAncestries)
    {
        $this->dbAncestryIds = $dbAncestries;
        return $this;
    }

    public function withPid(string $pid)
    {
        $this->pid = new Pid($pid);
        return $this;
    }

    public function withTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    public function withSearchTitle(string $searchTitle)
    {
        $this->searchTitle = $searchTitle;
        return $this;
    }

    public function withSynopses(Synopses $synopses)
    {
        $this->synopses = $synopses;
        return $this;
    }

    public function withImage(Image $image)
    {
        $this->image = $image;
        return $this;
    }

    public function withPromotionsCount(int $promotionsCount)
    {
        $this->promotionsCount = $promotionsCount;
        return $this;
    }

    public function withRelatedLinksCount(int $relatedLinksCount)
    {
        $this->relatedLinksCount = $relatedLinksCount;
        return $this;
    }

    public function withContributionsCount(int $contributionsCount)
    {
        $this->contributionsCount = $contributionsCount;
        return $this;
    }

    public function withOptions(Options $options)
    {
        $this->options = $options;
        return $this;
    }

    public function withParent(?Programme $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function withMasterBrand(?MasterBrand $masterbrand)
    {
        $this->masterBrand = $masterbrand;
        return $this;
    }
}
