<?php

namespace App\Redis;

use Doctrine\Common\Cache\RedisCache;
use Psr\Log\LoggerInterface;
use Redis;

class RedisLocalOnlyFactory extends RedisCache
{

    public static function createRedisInstance(LoggerInterface $logger)
    {
//        try {
            $redisInstance = new Redis();
            $redisInstance->connect('/tmp/redis.sock');
            $redisCacheInstance = new RedisLocalOnlyFactory();
            $redisCacheInstance->setRedis($redisInstance);
//        } catch (\Exception $e) {
//            // if redis fail, log the error and use a null adapter as a cache provider
//            $logger->error("Redis Error: " . $e->getMessage() . '. Using NullAdapter as a fallback cache adapter');
//            $cacheAdapter = new NullAdapter();
//        }
        return $redisCacheInstance;
    }
}
