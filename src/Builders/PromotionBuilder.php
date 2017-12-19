<?php
declare(strict_types = 1);
namespace App\Builders;

use BBC\ProgrammesPagesService\Domain\Entity\PromotableInterface;
use BBC\ProgrammesPagesService\Domain\Entity\Promotion;
use BBC\ProgrammesPagesService\Domain\Entity\RelatedLink;
use BBC\ProgrammesPagesService\Domain\ValueObject\Pid;
use BBC\ProgrammesPagesService\Domain\ValueObject\Synopses;
use Faker;

class PromotionBuilder implements BuilderInterface
{
    /** @var Pid */
    private $pid;

    /** @var PromotableInterface */
    private $promotedEntity;

    /** @var Synopses */
    private $synopses;

    /** @var string */
    private $title;

    /** @var string */
    private $url;

    /** @var int */
    private $weighting;

    /** @var bool */
    private $isSuperPromotion;

    /** @var RelatedLink[]|null */
    private $relatedLinks;

    private function __construct()
    {

    }

    public function withPid(string $pid)
    {
        $this->pid = new Pid($pid);
        return $this;
    }

    public function withPromotedEntity(PromotableInterface $promotedItem)
    {
        $this->promotedEntity = $promotedItem;
        return $this;
    }

    public function withTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    public function withSynopses(synopses $synopses)
    {
        $this->synopses = $synopses;
        return $this;
    }

    public function withUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function withWeighting(int $weighting)
    {
        $this->weighting = $weighting;
        return $this;
    }

    public function withIsSuperPromotion(bool $isSuperpromotion)
    {
        $this->isSuperPromotion = $isSuperpromotion;
        return $this;
    }

    public function withRelatedLinks(array $relatedLinks)
    {
        $this->relatedLinks = $relatedLinks;
        return $this;
    }

    public static function default()
    {
        $faker = Faker\Factory::create();

        $self = new self();
        $self->withPid('b00744wz')
        ->withPromotedEntity(ImageBuilder::default()->build())
        ->withSynopses(new Synopses($faker->text(20), $faker->text(100), $faker->text(250)))
        ->withTitle($faker->text)
        ->withUrl($faker->url)
        ->withWeighting($faker->numberBetween(1,5))
        ->withIswithIsSuperPromotion($faker->boolean)
        ->withRelatedLinks([
            // link external to BBC
            RelatedLinkBuilder::default()->withUri('http://something_that_is_not_bbc.net')->build(),
            // link internal to BBC
            RelatedLinkBuilder::default()->withUri('http://bbc.co.uk/something')->build(),
        ]);

        return $self;
    }

    public function build(): Promotion
    {
        return new Promotion(
            $this->pid,
            $this->promotedEntity,
            $this->title,
            $this->synopses,
            $this->url,
            $this->weighting,
            $this->isSuperPromotion,
            $this->relatedLinks
        );
    }
}
