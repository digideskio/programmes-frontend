<?php
declare(strict_types = 1);
namespace App\Builders;

use BBC\ProgrammesPagesService\Domain\Entity\Image;
use BBC\ProgrammesPagesService\Domain\ValueObject\Pid;
use Faker;

class ImageBuilder extends AbstractBuilder implements BuilderInterface
{
    /** @var Pid */
    private $pid;

    /** @var string */
    private $title;

    /** @var string */
    private $shortSynopsis;

    /** @var string */
    protected $longestSynopsis;

    /** @var string */
    private $type;

    /** @var string */
    private $extension;

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

    public function withShortSynopses(string $shortSynopses)
    {
        $this->shortSynopsis = $shortSynopses;
        return $this;
    }

    public function withLongestSynopsis(string $longestSynopses)
    {
        $this->longestSynopsis = $longestSynopses;
        return $this;
    }

    public function withType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    public function withExtension(string $extension)
    {
        $this->extension = $extension;
        return $this;
    }

    public static function default()
    {
        $faker = Faker\Factory::create();

        $self = new self();
        $self->withPid($faker->regexify('[0-9b-df-hj-np-tv-z]{8,15}'))
            ->withTitle($faker->sentence(3))
            ->withShortSynopses($faker->sentence(5))
            ->withLongestSynopsis($faker->sentence(30))
            ->withType('standard')
            ->withExtension('jpg');

        return $self;
    }

    public function build(): Image
    {
        return new Image(
            $this->pid,
            $this->title,
            $this->shortSynopsis,
            $this->longestSynopsis,
            $this->type,
            $this->extension
        );
    }
}
