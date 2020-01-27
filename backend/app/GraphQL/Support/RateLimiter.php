<?php

namespace App\GraphQL\Support;

use Illuminate\Support\Facades\Cache;

class RateLimiter {

    protected $namespace = '';

    protected $tolerance = 3;

    protected $ttl = 5;

    /**
     * Create new RateLimiter
     *
     * @param string $namespace
     * @param int    $tolerance
     * @param int    $ttl
     */
    public function __construct(string $namespace, int $tolerance, int $ttl)
    {
        $this->namespace = $namespace;
        $this->tolerance = $tolerance;
        $this->ttl = $ttl;
    }

    /**
     * Has user gone over threshold
     *
     * @return boolean
     */
    public function test(): bool
    {
        return Cache::get($this->cacheKey(), 0) > $this->tolerance;
    }

    /**
     * Update cache with auth fail event
     *
     * @return void
     */
    public function logEvent()
    {
        $key = $this->cacheKey();
        if (Cache::has($key)) {
            Cache::increment($key);
        } else {
            Cache::put($key, 1, $this->ttl);
        }
    }

    /**
     * Generate cache key for IP
     *
     * @return string
     */
    protected function cacheKey(): string
    {
        return sha1($this->namespace . $this->getIp());
    }

    /**
     * Get user ip
     *
     * @return ?string
     */
    protected function getIp(): ?string
    {
        return array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) ?
            array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']))
            : $_SERVER['REMOTE_ADDR'];
    }
}