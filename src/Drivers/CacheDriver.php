<?php

/*
 * This file is part of the pkg6/kv-settings
 *
 * (c) pkg6 <https://github.com/pkg6>
 *
 * (L) Licensed <https://opensource.org/license/MIT>
 *
 * (A) zhiqiang <https://www.zhiqiang.wang>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Pkg6\KvSettings\Drivers;

use Pkg6\KvSettings\Contracts\DriverInterface;
use Psr\SimpleCache\CacheInterface;

class CacheDriver implements DriverInterface
{
    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    protected $cache;

    protected $ttl = null;

    public function __construct(CacheInterface $cache, $ttl = null)
    {
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    public function forget($key)
    {
        return $this->cache->delete($key);
    }

    public function get($key, $default = null)
    {
        return $this->cache->get($key, $default);
    }

    public function has($key)
    {
        return $this->cache->has($key);
    }

    public function set(string $key, $value = null)
    {
        return $this->cache->set($key, $value, $this->ttl);
    }
}
