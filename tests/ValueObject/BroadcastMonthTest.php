<?php
declare(strict_types = 1);
namespace Tests\App\ValueObject;

use App\ValueObject\BroadcastMonth;
use BBC\ProgrammesPagesService\Domain\Entity\Service;
use BBC\ProgrammesPagesService\Domain\Enumeration\NetworkMediumEnum;
use BBC\ProgrammesPagesService\Domain\ValueObject\Pid;
use BBC\ProgrammesPagesService\Domain\ValueObject\Sid;
use Cake\Chronos\Chronos;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BroadcastMonthTest extends TestCase
{
    public function testTvMonth()
    {
        $day = new BroadcastMonth(
            new Chronos('2017-10-10 20:00:00Z'),
            NetworkMediumEnum::TV
        );

        $this->assertEquals(new Chronos('2017-10-1 06:00:00'), $day->start());
        $this->assertEquals(new Chronos('2017-10-31 23:59:59'), $day->end());
    }

    public function testTvMonthEarlyMorning()
    {
        $day = new BroadcastMonth(
            new Chronos('2017-10-1 03:00:00Z'),
            NetworkMediumEnum::TV
        );

        $this->assertEquals(new Chronos('2017-10-1 06:00:00'), $day->start());
        $this->assertEquals(new Chronos('2017-10-31 23:59:59'), $day->end());
    }

    public function testRadioMonth()
    {
        $day = new BroadcastMonth(
            new Chronos('2017-10-10 20:00:00Z'),
            NetworkMediumEnum::RADIO
        );

        $this->assertEquals(new Chronos('2017-10-1 00:00:00'), $day->start());
        $this->assertEquals(new Chronos('2017-10-31 23:59:59'), $day->end());
    }

    public function testRadioMonthEarlyMorning()
    {
        $day = new BroadcastMonth(
            new Chronos('2017-10-1 03:00:00Z'),
            NetworkMediumEnum::RADIO
        );

        $this->assertEquals(new Chronos('2017-10-1 00:00:00'), $day->start());
        $this->assertEquals(new Chronos('2017-10-31 23:59:59'), $day->end());
    }

    public function testBadNetworkMedium()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Called new BroadcastPeriod() with an invalid networkMedium. Expected one of "radio", "tv", "" but got "garbage"'
        );

        new BroadcastMonth(new Chronos('2017-10-10 03:00:00Z'), 'garbage');
    }

    public function testActiveOnWholeMonth()
    {
        $service = $this->createService('2017-01-01 00:00:00', '2017-03-03 23:23:59');
        $date = Chronos::create(2017, 2, 2, 9);
        $broadcastDay = new BroadcastMonth($date, NetworkMediumEnum::TV);

        $this->assertTrue($broadcastDay->serviceIsActiveInThisPeriod($service));
    }

    public function testActiveOnStartOfMonth()
    {
        $service = $this->createService('2017-01-01 13:00:00', '2017-01-03 23:23:59');
        $date = Chronos::create(2017, 1, 2, 9);
        $broadcastDay = new BroadcastMonth($date, NetworkMediumEnum::TV);

        $this->assertTrue($broadcastDay->serviceIsActiveInThisPeriod($service));
    }

    public function testActiveOnEndOfMonth()
    {
        $service = $this->createService('2017-01-31 13:00:00', '2017-02-02 08:00:00');
        $date = Chronos::create(2017, 1, 2, 9);
        $broadcastDay = new BroadcastMonth($date, NetworkMediumEnum::TV);

        $this->assertTrue($broadcastDay->serviceIsActiveInThisPeriod($service));
    }

    public function testActiveOnNoServiceStart()
    {
        $service = $this->createService(null, '2017-01-03 23:23:59');
        $date = Chronos::create(2017, 1, 2, 9);
        $broadcastDay = new BroadcastMonth($date, NetworkMediumEnum::TV);

        $this->assertTrue($broadcastDay->serviceIsActiveInThisPeriod($service));
    }

    public function testActiveOnNoServiceEnd()
    {
        $service = $this->createService('2017-01-01 00:00:00', null);
        $date = Chronos::create(2017, 1, 2, 9);
        $broadcastDay = new BroadcastMonth($date, NetworkMediumEnum::TV);

        $this->assertTrue($broadcastDay->serviceIsActiveInThisPeriod($service));
    }

    public function testNotActiveOn()
    {
        $service = $this->createService('2017-01-01 09:00:00', '2017-01-03 23:23:59');
        $date = Chronos::create(2016, 1, 2, 9);
        $broadcastDay = new BroadcastMonth($date, NetworkMediumEnum::TV);

        $this->assertFalse($broadcastDay->serviceIsActiveInThisPeriod($service));
    }

    public function testNotActiveOnOnlyServiceStart()
    {
        $service = $this->createService('2017-01-01 00:00:00', null);
        $date = Chronos::create(2016, 1, 2, 9);
        $broadcastDay = new BroadcastMonth($date, NetworkMediumEnum::TV);

        $this->assertFalse($broadcastDay->serviceIsActiveInThisPeriod($service));
    }

    public function testNotActiveOnOnlyServiceEnd()
    {
        $service = $this->createService(null, '2017-01-03 23:23:59');
        $date = Chronos::create(2018, 1, 2, 9);
        $broadcastDay = new BroadcastMonth($date, NetworkMediumEnum::TV);

        $this->assertFalse($broadcastDay->serviceIsActiveInThisPeriod($service));
    }

    public function testNotActiveWhenTVServiceEndsOnFirstBeforeSix()
    {
        $service = $this->createService('2017-01-01 09:00:00', '2017-02-01 05:00:00');
        $date = Chronos::create(2017, 2, 1, 0);
        $broadcastDay = new BroadcastMonth($date, NetworkMediumEnum::TV);

        $this->assertFalse($broadcastDay->serviceIsActiveInThisPeriod($service));
    }

    private function createService(?string $startDateTime, ?string $endDateTime): Service
    {
        $start = is_null($startDateTime) ? null : Chronos::createFromFormat('Y-m-d H:i:s', $startDateTime);
        $end = is_null($endDateTime) ? null : Chronos::createFromFormat('Y-m-d H:i:s', $endDateTime);
        return new Service(1, new Sid('sid'), new Pid('bcdfghjk'), 'title', 'shortName', 'urlKey', null, $start, $end);
    }
}
