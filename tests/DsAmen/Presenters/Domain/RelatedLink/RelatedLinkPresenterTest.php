<?php
declare(strict_types = 1);

namespace Tests\App\DsAmen\Organism\RelatedLink;

use App\DsAmen\Presenters\Domain\RelatedLink\RelatedLinkPresenter;
use BBC\ProgrammesPagesService\Domain\Entity\RelatedLink;
use PHPUnit\Framework\TestCase;

class RelatedLinkPresenterTest extends TestCase
{
    public function testExternalLink(): void
    {
        $link = $this->createMock(RelatedLink::class);
        $link->method('isExternal')->willReturn(true);
        $presenter = new RelatedLinkPresenter($link);
        $this->assertEquals('programmes_relatedlink_external', $presenter->getLinkLocation());
    }

    public function testInternalLink(): void
    {
        $link = $this->createMock(RelatedLink::class);
        $link->method('isExternal')->willReturn(false);
        $presenter = new RelatedLinkPresenter($link);
        $this->assertEquals('programmes_relatedlink_internal', $presenter->getLinkLocation());
    }
}
