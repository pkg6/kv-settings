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

namespace Pkg6\KvSettings;

use Pkg6\KvSettings\Contracts\ContextInterface;
use Pkg6\KvSettings\Contracts\DriverInterface;
use Pkg6\KvSettings\Traits\CacheTrait;
use Pkg6\KvSettings\Traits\EncrypterTrait;
use Pkg6\KvSettings\Traits\KeyTrait;
use Pkg6\KvSettings\Traits\ValueTrait;

class Settings
{
    use CacheTrait, EncrypterTrait, KeyTrait, ValueTrait;

    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @var ContextInterface
     */
    protected $context = null;

    public function __construct(DriverInterface $driver)
    {
        $this->setDriver($driver);
    }

    /**
     * @return \Pkg6\KvSettings\Contracts\DriverInterface
     */
    public function getDriver(): DriverInterface
    {
        if ( ! is_null($this->context)) {
            if (method_exists($this->driver, 'context')) {
                $this->driver->context($this->context);
            }
        }

        return $this->driver;
    }

    /**
     * @param \Pkg6\KvSettings\Contracts\DriverInterface $driver
     *
     * @return $this
     */
    public function setDriver(DriverInterface $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * @param \Pkg6\KvSettings\Contracts\ContextInterface|null $context
     *
     * @return $this
     */
    public function context(ContextInterface $context = null)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @param $key
     *
     * @return mixed
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function forget($key)
    {
        $key = $this->normalizeKey($key);
        $generatedKey = $this->getKeyForStorage($key);
        $driverResult = $this->getDriver()->forget($generatedKey);
        if ($this->cacheIsEnabled()) {
            $this->cacheDelete($this->getCacheKey($generatedKey));
        }
        $this->context();

        return $driverResult;
    }

    /**
     * @param $key
     * @param $default
     *
     * @return mixed|null
     *
     * @throws \throwable
     */
    public function get($key, $default = null)
    {
        $key = $this->normalizeKey($key);
        $generatedKey = $this->getKeyForStorage($key);
        if ($this->cacheIsEnabled()) {
            $value = $this->cacheRemember($this->getCacheKey($generatedKey), function () use ($generatedKey, $default) {
                return $this->getDriver()->get($generatedKey, $default);
            });
        } else {
            $value = $this->getDriver()->get($generatedKey, $default);
        }
        if ($value !== null && $value !== $default) {
            $value = $this->unserializeValue($this->decryptValue($value));
        }
        $this->context();

        return $value ?? $default;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function has($key)
    {
        $key = $this->normalizeKey($key);
        $has = $this->getDriver()->has($this->getKeyForStorage($key));
        $this->context();

        return $has;
    }

    /**
     * @param string $key
     * @param $value
     *
     * @return null
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function set(string $key, $value = null)
    {
        $key = $this->normalizeKey($key);
        // We really only need to update the value if is has changed
        // to prevent the cache being reset on the key.
        if ( ! $this->shouldSetNewValue($key, $value)) {
            $this->context();

            return null;
        }
        $generatedKey = $this->getKeyForStorage($key);
        $serializedValue = $this->serializeValue($value);

        $driverResult = $this->getDriver()->set(
            $generatedKey,
            $this->encryptionIsEnabled() ? $this->encryptValue($serializedValue) : $serializedValue
        );
        if ($this->cacheIsEnabled()) {
            $this->cacheDelete($this->getCacheKey($generatedKey));
        }
        $this->context();

        return $driverResult;
    }

    /**
     * @param string $key
     * @param bool $default
     *
     * @return bool
     *
     * @throws \throwable
     */
    public function isFalse(string $key, $default = false): bool
    {
        $value = $this->get($key, $default);

        return $value === false || $value === '0' || $value === 1;
    }

    /**
     * @param string $key
     * @param bool $default
     *
     * @return bool
     *
     * @throws \throwable
     */
    public function isTrue(string $key, $default = true): bool
    {
        $value = $this->get($key, $default);

        return $value === true || $value === '1' || $value === 1;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function normalizeKey(string $key): string
    {
        return $key;
    }

    /**
     * @param string $key
     * @param $newValue
     *
     * @return bool
     *
     * @throws \throwable
     */
    protected function shouldSetNewValue(string $key, $newValue): bool
    {
        if ( ! $this->cacheIsEnabled()) {
            return true;
        }
        $currentContext = $this->context;
        $currentValue = $this->get($key);
        $shouldUpdate = $currentValue !== $newValue || ! $this->has($key);
        // Now that we've made our calls, we can set our context back to what it was.
        $this->context($currentContext);

        return $shouldUpdate;
    }
}
