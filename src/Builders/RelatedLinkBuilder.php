<?php
namespace App\Builders;

use BBC\ProgrammesPagesService\Domain\Entity\RelatedLink;
use Faker;

class RelatedLinkBuilder extends AbstractBuilder implements BuilderInterface
{
    /** @var string */
    private $title;

    /** @var string */
    private $uri;

    /** @var string */
    private $shortSynopsis;

    /** @var string */
    private $longestSynopsis;

    /** @var string */
    private $type;

    /** @var bool */
    private $isExternal;

    public function withTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    public function withUri(string $uri)
    {
        $this->uri = $uri;
        return $this;
    }

    public function withShortSynopsis(string $shortSynopsis)
    {
        $this->shortSynopsis = $shortSynopsis;
        return $this;
    }

    public function withLongestSynopsis(string $longestSynopsis)
    {
        $this->longestSynopsis = $longestSynopsis;
        return $this;
    }

    public function withType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    public function withIsExternal(bool $isExternal)
    {
        $this->isExternal = $isExternal;
        return $this;
    }

    public static function internalLink()
    {
        $faker = Faker\Factory::create();

        $self = new self();
        $self->withTitle($faker->sentence(3))
             ->withUri('https://www.bbc.co.uk/something')
             ->withShortSynopsis($faker->sentence(5))
             ->withLongestSynopsis($faker->sentence(30))
             ->withType($faker->text(5))
             ->withIsExternal(false);

        return $self;
    }

    public static function externalLink()
    {
        $faker = Faker\Factory::create();

        $self = new self();
        $self->withTitle($faker->sentence(3))
             ->withUri($faker->url)
             ->withShortSynopsis($faker->sentence(5))
             ->withLongestSynopsis($faker->sentence(30))
             ->withType($faker->text)
             ->withIsExternal(true);

        return $self;
    }

    public static function default()
    {
        // by default we create an external link
        return self::externalLink();
    }

    public function build(): RelatedLink
    {
        return new RelatedLink(
            $this->title,
            $this->uri,
            $this->shortSynopsis,
            $this->longestSynopsis,
            $this->type,
            $this->isExternal
        );
    }
}
