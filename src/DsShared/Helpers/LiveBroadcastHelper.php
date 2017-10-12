<?php
declare(strict_types = 1);
namespace App\DsShared\Helpers;

use BBC\ProgrammesPagesService\Domain\ApplicationTime;
use BBC\ProgrammesPagesService\Domain\Entity\CollapsedBroadcast;
use BBC\ProgrammesPagesService\Domain\Entity\Service;
use Cake\Chronos\Chronos;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LiveBroadcastHelper
{
    private const LIVE_SERVICE_URLS = [
        //sid                                   => [route, [...arguments]],
        'bbc_one_london'                        => ['iplayer_live', ['sid' => 'bbcone']],
        'bbc_one_scotland'                      => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'scotland']],
        'bbc_one_wales'                         => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'wales']],
        'bbc_one_northern_ireland'              => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'northern_ireland']],
        'bbc_one_cambridge'                     => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'cambridge']],
        'bbc_one_channel_islands'               => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'channel_islands']],
        'bbc_one_east'                          => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'east']],
        'bbc_one_east_midlands'                 => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'east_midlands']],
        'bbc_one_east_yorkshire'                => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'east_yorkshire']],
        'bbc_one_north_east'                    => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'north_east']],
        'bbc_one_north_west'                    => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'north_west']],
        'bbc_one_oxford'                        => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'oxford']],
        'bbc_one_south'                         => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'south']],
        'bbc_one_south_east'                    => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'south_east']],
        'bbc_one_south_west'                    => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'south_west']],
        'bbc_one_west'                          => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'west']],
        'bbc_one_west_midlands'                 => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'west_midlands']],
        'bbc_one_yorks'                         => ['iplayer_live', ['sid' => 'bbcone', 'area' => 'yorks']],
        'bbc_two_england'                       => ['iplayer_live', ['sid' => 'bbctwo']],
        // @TODO are these correct for bbc_two regions
        'bbc_two_northern_ireland_digital'      => ['iplayer_live', ['sid' => 'bbctwo', 'area' => 'northern_ireland_digital']],
        'bbc_two_wales_digital'                 => ['iplayer_live', ['sid' => 'bbctwo', 'area' => 'wales_digital']],
        'bbc_two_scotland'                      => ['iplayer_live', ['sid' => 'bbctwo', 'area' => 'scotland']],
        'bbc_three'                             => ['iplayer_live', ['sid' => 'bbcthree']],
        'bbc_four'                              => ['iplayer_live', ['sid' => 'bbcfour']],
        'cbbc'                                  => ['iplayer_live', ['sid' => 'cbbc']],
        'cbeebies'                              => ['iplayer_live', ['sid' => 'cbeebies']],
        'bbc_news24'                            => ['iplayer_live', ['sid' => 'bbcnews']],
        'bbc_parliament'                        => ['iplayer_live', ['sid' => 'bbcparliament']],
        'bbc_alba'                              => ['iplayer_live', ['sid' => 'bbcalba']],
        'bbc_radio_one'                         => ['radio_player', ['sid' => 'bbc_radio_one']],
        'bbc_radio_one_vintage'                 => ['radio_player', ['sid' => 'bbc_radio_one_vintage']],
        'bbc_1xtra'                             => ['radio_player', ['sid' => 'bbc_1xtra']],
        'bbc_radio_two'                         => ['radio_player', ['sid' => 'bbc_radio_two']],
        'bbc_radio_three'                       => ['radio_player', ['sid' => 'bbc_radio_three']],
        'bbc_radio_fourfm'                      => ['radio_player', ['sid' => 'bbc_radio_fourfm']],
        'bbc_radio_fourlw'                      => ['radio_player', ['sid' => 'bbc_radio_fourlw']],
        'bbc_radio_four_extra'                  => ['radio_player', ['sid' => 'bbc_radio_four_extra']],
        'bbc_radio_five_live'                   => ['radio_player', ['sid' => 'bbc_radio_five_live']],
        'bbc_radio_five_live_sports_extra'      => ['radio_player', ['sid' => 'bbc_radio_five_live_sports_extra']],
        'bbc_6music'                            => ['radio_player', ['sid' => 'bbc_6music']],
        'bbc_7'                                 => ['radio_player', ['sid' => 'bbc_7']],
        'bbc_asian_network'                     => ['radio_player', ['sid' => 'bbc_asian_network']],
        'bbc_world_service'                     => ['radio_player', ['sid' => 'bbc_world_service']],
        'bbc_radio_scotland_fm'                 => ['radio_player', ['sid' => 'bbc_radio_scotland_fm']],
        'bbc_radio_nan_gaidheal'                => ['radio_player', ['sid' => 'bbc_radio_nan_gaidheal']],
        'bbc_radio_ulster'                      => ['radio_player', ['sid' => 'bbc_radio_ulster']],
        'bbc_radio_foyle'                       => ['radio_player', ['sid' => 'bbc_radio_foyle']],
        'bbc_radio_wales_fm'                    => ['radio_player', ['sid' => 'bbc_radio_wales_fm']],
        'bbc_radio_cymru'                       => ['radio_player', ['sid' => 'bbc_radio_cymru']],
        'bbc_radio_cymru_mwy'                   => ['radio_player', ['sid' => 'bbc_radio_cymru_mwy']],
        'bbc_london'                            => ['radio_player', ['sid' => 'bbc_london']],
        'bbc_radio_berkshire'                   => ['radio_player', ['sid' => 'bbc_radio_berkshire']],
        'bbc_radio_bristol'                     => ['radio_player', ['sid' => 'bbc_radio_bristol']],
        'bbc_radio_cambridge'                   => ['radio_player', ['sid' => 'bbc_radio_cambridge']],
        'bbc_radio_cornwall'                    => ['radio_player', ['sid' => 'bbc_radio_cornwall']],
        'bbc_radio_coventry_warwickshire'       => ['radio_player', ['sid' => 'bbc_radio_coventry_warwickshire']],
        'bbc_radio_cumbria'                     => ['radio_player', ['sid' => 'bbc_radio_cumbria']],
        'bbc_radio_derby'                       => ['radio_player', ['sid' => 'bbc_radio_derby']],
        'bbc_radio_devon'                       => ['radio_player', ['sid' => 'bbc_radio_devon']],
        'bbc_radio_essex'                       => ['radio_player', ['sid' => 'bbc_radio_essex']],
        'bbc_radio_gloucestershire'             => ['radio_player', ['sid' => 'bbc_radio_gloucestershire']],
        'bbc_radio_guernsey'                    => ['radio_player', ['sid' => 'bbc_radio_guernsey']],
        'bbc_radio_hereford_worcester'          => ['radio_player', ['sid' => 'bbc_radio_hereford_worcester']],
        'bbc_radio_humberside'                  => ['radio_player', ['sid' => 'bbc_radio_humberside']],
        'bbc_radio_jersey'                      => ['radio_player', ['sid' => 'bbc_radio_jersey']],
        'bbc_radio_kent'                        => ['radio_player', ['sid' => 'bbc_radio_kent']],
        'bbc_radio_lancashire'                  => ['radio_player', ['sid' => 'bbc_radio_lancashire']],
        'bbc_radio_leeds'                       => ['radio_player', ['sid' => 'bbc_radio_leeds']],
        'bbc_radio_leicester'                   => ['radio_player', ['sid' => 'bbc_radio_leicester']],
        'bbc_radio_lincolnshire'                => ['radio_player', ['sid' => 'bbc_radio_lincolnshire']],
        'bbc_radio_manchester'                  => ['radio_player', ['sid' => 'bbc_radio_manchester']],
        'bbc_radio_merseyside'                  => ['radio_player', ['sid' => 'bbc_radio_merseyside']],
        'bbc_radio_newcastle'                   => ['radio_player', ['sid' => 'bbc_radio_newcastle']],
        'bbc_radio_norfolk'                     => ['radio_player', ['sid' => 'bbc_radio_norfolk']],
        'bbc_radio_northampton'                 => ['radio_player', ['sid' => 'bbc_radio_northampton']],
        'bbc_radio_nottingham'                  => ['radio_player', ['sid' => 'bbc_radio_nottingham']],
        'bbc_radio_oxford'                      => ['radio_player', ['sid' => 'bbc_radio_oxford']],
        'bbc_radio_sheffield'                   => ['radio_player', ['sid' => 'bbc_radio_sheffield']],
        'bbc_radio_shropshire'                  => ['radio_player', ['sid' => 'bbc_radio_shropshire']],
        'bbc_radio_solent'                      => ['radio_player', ['sid' => 'bbc_radio_solent']],
        'bbc_radio_somerset_sound'              => ['radio_player', ['sid' => 'bbc_radio_somerset_sound']],
        'bbc_radio_stoke'                       => ['radio_player', ['sid' => 'bbc_radio_stoke']],
        'bbc_radio_suffolk'                     => ['radio_player', ['sid' => 'bbc_radio_suffolk']],
        'bbc_radio_surrey'                      => ['radio_player', ['sid' => 'bbc_radio_surrey']],
        'bbc_radio_sussex'                      => ['radio_player', ['sid' => 'bbc_radio_sussex']],
        'bbc_radio_swindon'                     => ['radio_player', ['sid' => 'bbc_radio_swindon']],
        'bbc_radio_wiltshire'                   => ['radio_player', ['sid' => 'bbc_radio_wiltshire']],
        'bbc_radio_york'                        => ['radio_player', ['sid' => 'bbc_radio_york']],
        'bbc_southern_counties_radio'           => ['radio_player', ['sid' => 'bbc_southern_counties_radio']],
        'bbc_tees'                              => ['radio_player', ['sid' => 'bbc_tees']],
        'bbc_three_counties_radio'              => ['radio_player', ['sid' => 'bbc_three_counties_radio']],
        'bbc_wm'                                => ['radio_player', ['sid' => 'bbc_wm']],
        'bbc_music_jazz'                        => ['radio_player', ['sid' => 'bbc_music_jazz']],
        'bbc_radio_two_fifties'                 => ['radio_player', ['sid' => 'bbc_radio_two_fifties']],
        'bbc_radio_scotland_music_extra'        => ['radio_player', ['sid' => 'bbc_radio_scotland_music_extra']],
        'bbc_radio_two_country'                 => ['radio_player', ['sid' => 'bbc_radio_two_country']],

        'bbc_afrique_radio'                     => ['worldservice_liveradio', ['language' => 'afrique', 'sid' => 'bbc_afrique_radio']],
        'bbc_gahuza_radio'                      => ['worldservice_liveradio', ['language' => 'gahuza', 'sid' => 'bbc_gahuza_radio']],
        'bbc_hausa_radio'                       => ['worldservice_liveradio', ['language' => 'hausa', 'sid' => 'bbc_hausa_radio']],
        'bbc_somali_radio'                      => ['worldservice_liveradio', ['language' => 'somali', 'sid' => 'bbc_somali_radio']],
        'bbc_swahili_radio'                     => ['worldservice_liveradio', ['language' => 'swahili', 'sid' => 'bbc_swahili_radio']],

        'bbc_afghan_radio'                      => null,
        'bbc_cantonese_radio'                   => null,

        'bbc_russian_radio'                     => ['worldservice_liveradio', ['language' => 'russian', 'sid' => 'bbc_russian_radio']],
        'bbc_persian_radio'                     => ['worldservice_liveradio', ['language' => 'persian', 'sid' => 'bbc_persian_radio']],
        'bbc_dari_radio'                        => ['worldservice_liveradio', ['language' => 'persian', 'sid' => 'bbc_dari_radio']],
        'bbc_pashto_radio'                      => ['worldservice_liveradio', ['language' => 'pashto', 'sid' => 'bbc_pashto_radio']],
        'bbc_arabic_radio'                      => ['worldservice_liveradio', ['language' => 'arabic', 'sid' => 'bbc_arabic_radio']],
        'bbc_uzbek_radio'                       => ['worldservice_liveradio', ['language' => 'uzbek', 'sid' => 'bbc_uzbek_radio']],
        'bbc_kyrgyz_radio'                      => ['worldservice_liveradio', ['language' => 'kyrgyz', 'sid' => 'bbc_kyrgyz_radio']],
        'bbc_urdu_radio'                        => ['worldservice_liveradio', ['language' => 'urdu', 'sid' => 'bbc_urdu_radio']],
        'bbc_burmese_radio'                     => ['worldservice_liveradio', ['language' => 'burmese', 'sid' => 'bbc_burmese_radio']],
        'bbc_hindi_radio'                       => ['worldservice_liveradio', ['language' => 'hindi', 'sid' => 'bbc_hindi_radio']],
        'bbc_bangla_radio'                      => ['worldservice_liveradio', ['language' => 'bengali', 'sid' => 'bbc_bangla_radio']],
        'bbc_nepali_radio'                      => ['worldservice_liveradio', ['language' => 'nepali', 'sid' => 'bbc_nepali_radio']],
        'bbc_tamil_radio'                       => ['worldservice_liveradio', ['language' => 'tamil', 'sid' => 'bbc_tamil_radio']],
        'bbc_sinhala_radio'                     => ['worldservice_liveradio', ['language' => 'sinhala', 'sid' => 'bbc_sinhala_radio']],

        'bbc_indonesian_radio'                  => ['worldservice_liveradio', ['language' => 'indonesia', 'sid' => 'bbc_indonesian_radio']],

        'bbc_radio_solent_west_dorset'          => ['radio_player', ['sid' => 'bbc_radio_solent_west_dorset']],
        'bbc_radio_scotland_mw'                 => ['radio_player', ['sid' => 'bbc_radio_scotland_mw']],
        'bbc_world_service_americas'            => ['radio_player', ['sid' => 'bbc_world_service_americas']],
        'bbc_world_service_australasia'         => ['radio_player', ['sid' => 'bbc_world_service_australasia']],
        'bbc_world_service_east_africa'         => ['radio_player', ['sid' => 'bbc_world_service_east_africa']],
        'bbc_world_service_east_asia'           => ['radio_player', ['sid' => 'bbc_world_service_east_asia']],
        'bbc_world_service_europe'              => ['radio_player', ['sid' => 'bbc_world_service_europe']],
        'bbc_world_service_south_asia'          => ['radio_player', ['sid' => 'bbc_world_service_south_asia']],
        'bbc_world_service_west_africa'         => ['radio_player', ['sid' => 'bbc_world_service_west_africa']],
        'cbeebies_radio'                        => ['radio_player', ['sid' => 'cbeebies_radio']],
        'bbc_radio_two_eurovision'              => ['radio_player', ['sid' => 'bbc_radio_two_eurovision']],
    ];

    /** @var Chronos */
    private $now;

    /** @var Chronos */
    private $sixMinutesFromNow;

    /** @var UrlGeneratorInterface */
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function simulcastUrl(
        CollapsedBroadcast $collapsedBroadcast,
        ?Service $preferredService = null,
        array $additionalUrlParameters = []
    ): string {
        $servicesBySid = $this->getServicesBySid($collapsedBroadcast);
        if ($preferredService) {
            $preferredServiceId = (string) $preferredService->getSid();
            if (isset($servicesBySid[$preferredServiceId]) && isset(self::LIVE_SERVICE_URLS[$preferredServiceId])) {
                $params = array_merge(self::LIVE_SERVICE_URLS[$preferredServiceId][1], $additionalUrlParameters);
                return $this->router->generate(
                    self::LIVE_SERVICE_URLS[$preferredServiceId][0],
                    $params,
                    UrlGeneratorInterface::ABSOLUTE_URL
                );
            }
        }

        // Go through our list in order. We prefer default service (e.g. bbc_one_london) over regional ones etc.
        foreach (self::LIVE_SERVICE_URLS as $sid => $parameters) {
            if (isset($servicesBySid[$sid])) {
                $params = array_merge($parameters[1], $additionalUrlParameters);
                return $this->router->generate(
                    $parameters[0],
                    $params,
                    UrlGeneratorInterface::ABSOLUTE_URL
                );
            }
        }

        return '';
    }

    public function isWatchableLive(CollapsedBroadcast $collapsedBroadcast, bool $advancedLive = false): bool
    {
        if ($collapsedBroadcast->isBlanked() || !$this->simulcastUrl($collapsedBroadcast)) {
            return false;
        }

        return $this->isOnNowIsh($collapsedBroadcast, $advancedLive);
    }

    private function isOnNowIsh(CollapsedBroadcast $collapsedBroadcast, bool $advancedLive = false): bool
    {
        $startBefore = $endAfter = ApplicationTime::getTime();
        if ($advancedLive) {
            // This is used to show a link to a live broadcast before it starts
            // (caching etc)
            $startBefore = $this->getSixMinutesFromNow();
        }
        if ($collapsedBroadcast->getStartAt() <= $startBefore && $endAfter < $collapsedBroadcast->getEndAt()) {
            return true;
        }
        return false;
    }

    private function getServicesBySid(CollapsedBroadcast $collapsedBroadcast): array
    {
        $services = $collapsedBroadcast->getServices();
        $servicesBySid = [];
        foreach ($services as $service) {
            $servicesBySid[(string) $service->getSid()] = $service;
        }
        return $servicesBySid;
    }

    private function getSixMinutesFromNow(): Chronos
    {
        if (!$this->sixMinutesFromNow) {
            $this->sixMinutesFromNow = ApplicationTime::getTime()->addMinutes(6);
        }
        return $this->sixMinutesFromNow;
    }
}
