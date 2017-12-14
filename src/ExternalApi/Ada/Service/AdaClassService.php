<?php
declare(strict_types=1);

namespace App\ExternalApi\Ada\Service;

use App\ExternalApi\Ada\Domain\AdaClass;
use App\ExternalApi\Ada\Mapper\AdaClassMapper;
use BBC\ProgrammesPagesService\Cache\CacheInterface;
use BBC\ProgrammesPagesService\Domain\Entity\Programme;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

/**
 * For querying Ada Classes, which are the overarching containers that
 * ProgrammeItems may be related to.
 *
 * For more info see: https://confluence.dev.bbc.co.uk/display/ADA/API+Specification
 *
 * In the API there are two types of class: Tag and Category however we do not
 * differentiate between these on the front end. This means that when we reqest
 * data we request twice as many results as we need because we may have to
 * deduplicate Tags and Categories with the same ID. In
 *
 * e.g. If we want 2 items, we have to request 4 items as we may get this
 * result set:
 *
 * [
 *   {"id":"one", type:"tag"},
 *   {"id":"one", type:"category"},
 *   {"id":"two", type:"tag"},
 *   {"id":"two", type:"category"}
 * ]
 *
 * Which we would then deduplicate into the returned data:
 * [
 *   new AdaClass("one"),
 *   new AdaClass("two")
 * ]
 *
 */
class AdaClassService
{
    /** @var ClientInterface */
    private $client;

    /** @var CacheInterface */
    private $cache;

    /** @var AdaClassMapper */
    private $mapper;

    /** @var LoggerInterface */
    private $logger;

    /** @var string */
    private $baseUrl;

    public function __construct(
        ClientInterface $client,
        CacheInterface $cache,
        AdaClassMapper $mapper,
        LoggerInterface $logger,
        string $baseUrl
    ) {
        $this->client = $client;
        $this->cache = $cache;
        $this->mapper = $mapper;
        $this->logger = $logger;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return AdaClass[]
     */
    public function findRelatedClassesByContainer(
        Programme $programme,
        bool $countWithinTleo = true
    ): array {
        $limit = 5;

        // If $countWithinTleo is true, then the programme_item_count returned
        // shall be the number of items with a tag WITHIN the TLEO.
        // If $countWithinTleo is false, then the programme_item_count returned
        // shall be the number of items across the entire BBC
        $contextPid = ($countWithinTleo ? (string) $programme->getTleo()->getPid() : null);

        $stringPid = (string) $programme->getPid();

        $cacheKey = $this->cache->keyHelper(__CLASS__, __FUNCTION__, $stringPid, $countWithinTleo);
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        // Request twice as many items as the desired limit becase we may remove
        // some classes due to duplication (see class comment)
        $url = $this->buildRequestUrl($stringPid, $contextPid, null, null, 2, 'rank', 'descending', $limit * 2);

        try {
            $response = $this->client->request('GET', $url);
        } catch (GuzzleException $e) {
            $this->logger->warning('Invalid response from Ada API');

            if ($e instanceof ClientException && $e->getResponse() && $e->getResponse()->getStatusCode() === 404) {
                // 404s get cached for a shorter time
                $this->cache->setItem($cacheItem, [], CacheInterface::NORMAL);
            }

            return [];
        }

        $response = json_decode($response->getBody()->getContents(), true);
        $result = array_slice($this->deduplicateClasses($this->mapItems($response)), 0, $limit);
        $this->cache->setItem($cacheItem, $result, CacheInterface::MEDIUM);

        return $result;
    }

    private function buildRequestUrl(
        ?string $programmePid = null,
        ?string $contextPid = null,
        ?string $countItemType = null,
        ?string $type = null,
        ?int $threshold = null,
        ?string $order = null,
        ?string $orderDirection = 'descending',
        int $limit = 10,
        int $page = 1
    ) {
        return $this->baseUrl . '/classes?page=' . $page . '&page_size=' . $limit .
            ($programmePid ? '&programme=' . $programmePid : '') .
            ($contextPid ? '&count_context=' . $contextPid : '') .
            ($countItemType ? '&count_item_type=' . $countItemType : '') .
            ($type ? '&type=' . $type : '') .
            ($threshold ? '&threshold=' . $threshold : '') .
            ($order ? '&order=' . $order : '') .
            ($orderDirection ? '&direction=' . $orderDirection : '');
    }

    /**
     * @return AdaClass[]
     */
    private function mapItems(array $response)
    {
        return array_map([$this->mapper, 'mapItem'], $response['items']);
    }

    /**
     * Classes that are returned from the API may be of type "tag" or "category"
     * however in our listings we do not differentiate between these two. Thus
     * if ADA returns a list of classes that contain a tag and a category with
     * the same id we should remove one of them to avoid it looking like there
     * are duplicate items in the list.
     *
     * @param AdaClass[] $classes
     * @return AdaClass[]
     */
    private function deduplicateClasses(array $classes)
    {
        $uniqueIds = [];
        $uniqueClasses = [];

        foreach ($classes as $class) {
            if (!array_key_exists($class->getId(), $uniqueIds)) {
                $uniqueIds[$class->getId()] = true;
                $uniqueClasses[] = $class;
            }
        }
        return $uniqueClasses;
    }
}
